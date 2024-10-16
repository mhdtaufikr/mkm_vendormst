<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerMaster;
use App\Models\Dropdown;
use App\Models\CustomerChange;
use App\Models\ApprovalLogCustomer;
use App\Models\ApprovalRoute;
use App\Mail\CustomerApprovalMail;
use App\Mail\CustomerRemandMail;
use App\Mail\CustomerCompletionMail;
use App\Mail\CustomerFormCompleted;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CustomerMstController extends Controller
{
    public function index()
{
    // Fetch all customer masters with their changes and related approvals, sorted by the newest data
    $items = CustomerMaster::with(['changes', 'changes.approvalLogs.approver'])
        ->orderBy('created_at', 'desc') // Sort by created_at in descending order (newest first)
        ->get();

    // Fetch all users to map id to name
    $users = User::select('id', 'username')->get()->pluck('username', 'id');

    // Fetch distinct approval routes based on level
    $approvalRoutes = ApprovalRoute::select('level', 'dept', 'name', 'action', 'requester')
        ->orderBy('level', 'asc')
        ->get();

    // Determine approval status for each customer change
    foreach ($items as $item) {
        foreach ($item->changes as $change) {
            // Get the name of the creator using the created_by id from the customer changes
            $createdByName = $users->get($change->created_by);

            // Filter approval routes based on the mapped name from the users table
            $filteredRoutes = $approvalRoutes->filter(function ($route) use ($createdByName) {
                // If 'requester' matches 'created_by' name or 'requester' is null (default route)
                return $route->requester === $createdByName || is_null($route->requester);
            });

            $currentLevel = $change->level; // The current level of the customer change process

            // Add status and timestamp to each distinct route in the filtered approval routes
            foreach ($filteredRoutes as $route) {
                // Find logs for this approver and level
                $approvalLog = $change->approvalLogs->firstWhere('approver.name', $route->name);

                if ($route->level < $currentLevel) {
                    $route->status = 'Approved';
                    $route->timestamp = $approvalLog ? $approvalLog->approval_timestamp : null;
                } elseif ($route->level == $currentLevel) {
                    // If the approval log exists and the action is "Approved", set status as approved
                    if ($approvalLog && $approvalLog->approval_action === 'Approved') {
                        $route->status = 'Approved';
                        $route->timestamp = $approvalLog->approval_timestamp;
                    } else {
                        $route->status = 'Pending'; // If not approved, show as Pending
                        $route->timestamp = null; // No timestamp for pending actions
                    }
                } else {
                    $route->status = 'Not yet reviewed';
                    $route->timestamp = null; // No timestamp for not reviewed actions
                }
            }

            // Attach the filtered approval routes to the change for display in the view
            $change->approvalRoutes = $filteredRoutes->map(function ($route) {
                return clone $route; // Clone the route to avoid reference issues
            });

            // Determine all pending approvers for the current level
            $pendingApprovers = $filteredRoutes->where('status', 'Pending')->pluck('name')->toArray();
            if (!empty($pendingApprovers)) {
                // Concatenate all pending names with " & " separator
                $change->latestPending = implode(' & ', $pendingApprovers);
            } else {
                $change->latestPending = 'Approved';
            }
        }
    }

    // Fetch dropdown data for form filtering or selection
    $dropdown = Dropdown::where('category', 'Form')
        ->orderBy('name_value', 'asc')
        ->get();

    // Return the view with the data
    return view('master.list', compact('items', 'dropdown'));
}




    public function form()
    {
        $customerAG = Dropdown::where('category', 'Customer AG')->get();
        $tax = Dropdown::where('category', 'Withholding Tax')->get();
        $types = Dropdown::where('category', 'Type')->get(); // Assuming 'Change Type' is the category
        $title = Dropdown::where('category', 'Title')->get(); // Assuming 'Title' is the category
        $response = Http::get('https://restcountries.com/v3.1/all');
        $countries = $response->json();

        // Sort countries alphabetically by name
        usort($countries, function ($a, $b) {
            return strcasecmp($a['name']['common'], $b['name']['common']);
        });

        // Fetch currencies from API
        $currencyResponse = Http::get('https://openexchangerates.org/api/currencies.json?app_id=YOUR_API_KEY');
        $currencies = $currencyResponse->json();

        // Convert the currencies object to an array of [code, name] pairs
        $currencyArray = [];
        foreach ($currencies as $code => $name) {
            $currencyArray[] = ['code' => $code, 'name' => $name];
        }

        // Sort the array alphabetically by currency name
        usort($currencyArray, function ($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });

        return view('form.mstcustomer', compact('customerAG', 'tax', 'types', 'title', 'countries', 'currencyArray'));
    }

    public function store(Request $request)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'company_code' => 'required|string|max:255',
        'account_group' => 'nullable|array',
        'account_group.*' => 'nullable|string|max:255',
        'customer_name' => 'nullable|string|max:255',
        'title' => 'nullable|string|max:255',
        'name' => 'nullable|string|max:255',
        'search_term_1' => 'nullable|string|max:255',
        'search_term_2' => 'nullable|string|max:255',
        'street' => 'nullable|string|max:255',
        'house_number' => 'nullable|string|max:255',
        'postal_code' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'country' => 'nullable|string|max:255',
        'region' => 'nullable|string|max:255',
        'po_box' => 'nullable|string|max:255',
        'telephone' => 'nullable|string|max:255',
        'fax' => 'nullable|string|max:255',
        'email' => 'nullable|string|max:255',
        'tax_code' => 'nullable|string|max:255',
        'npwp' => 'nullable|string|max:255',
        'bank_key' => 'nullable|string|max:255',
        'bank_account' => 'nullable|string|max:255',
        'account_holder' => 'nullable|string|max:255',
        'bank_region' => 'nullable|string|max:255',
        'currency' => 'nullable|string|max:45',
        'recon_account' => 'nullable|string|max:255',
        'confirm_with' => 'nullable|string|max:45',
        'email_no_handphone' => 'nullable|string|max:45',
        'confirmed_by' => 'nullable|string|max:45',
        'date' => 'required|date',
        'change_type' => 'required|string|in:Create,Block,Update',
        'previous_sap_customernumber' => 'nullable|string|max:255',
        'Remarks' => 'nullable|string',
        'withholding_tax' => 'nullable|array',
        'withholding_tax.*' => 'nullable|string|max:255',
        'payment_block' => 'nullable|string|in:on',
        'cash_management_group' => 'nullable|string|max:255',
        'payment_terms' => 'nullable|string|max:255',
        'payment_method' => 'nullable|string|max:255',
        'sort_key' => 'nullable|string|max:255',
    ]);

    // Handle multiple file uploads if exists
    $filePaths = [];
    if ($request->hasFile('files')) {
        foreach ($request->file('files') as $file) {
            // Generate a unique file name and move the file to the public directory
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/upload');
            $file->move($destinationPath, $fileName);

            // Add the file path to the array
            $filePaths[] = 'assets/upload/' . $fileName;
        }
        // Store the file paths as a JSON string
        $validatedData['file'] = json_encode($filePaths);
    }

    // Extract and prepare account_group and withholding_tax for saving as pipe-separated strings
    $accountGroup = isset($validatedData['account_group']) ? implode(',', $validatedData['account_group']) : null;
    $withholdingTax = isset($validatedData['withholding_tax']) ? implode('|', $validatedData['withholding_tax']) : null;

    // Handle the payment_block checkbox
    $paymentBlock = isset($validatedData['payment_block']) ? 1 : 0;

    // Create a new customer record in customer_masters table
    $customerMaster = CustomerMaster::create(array_merge($validatedData, [
        'account_group' => $accountGroup,
        'withholding_tax' => $withholdingTax,
        'payment_block' => $paymentBlock,
        'confirm_info' => $request->email_no_handphone,
        'confirm_by' => $request->confirm_with,
    ]));

    // Handle customer changes if necessary
    if (in_array($validatedData['change_type'], ['Create', 'Block', 'Update'])) {
        $customerChangeData = [
            'customer_id' => $customerMaster->id,
            'change_type' => $validatedData['change_type'],
            'previous_sap_customer_number' => $validatedData['previous_sap_customernumber'],
            'remarks' => $validatedData['Remarks'],
            'status' => 'Pending', // Set initial status as pending
            'created_by' => Auth::id(), // Assuming you have authentication and get the current user's ID
            'level' => 1,
        ];

        // Create a new customer change record
        CustomerChange::create($customerChangeData);
    }

    $customerChange = CustomerChange::where('customer_id', $customerMaster->id)->first();

    // Create a new approval log entry in approval_log_customers table
    $approvalLog = new ApprovalLogCustomer();
    $approvalLog->customer_change_id = $customerChange->id;
    $approvalLog->approver_id = Auth::id();
    $approvalLog->approval_action = 'Submitter';
    $approvalLog->approval_comments = 'Initiate';
    $approvalLog->approval_timestamp = now()->toDateTimeString(); // Current timestamp
    $approvalLog->approval_level = 0;
    $approvalLog->save();

    // Handle approval routing
    $user = Auth::user();
    $userDept = $user->dept;
    $userName = $user->username;

    // Find approvers based on dept and requester
    $approvalRoutes = ApprovalRoute::where('dept', $userDept)
        ->where('requester', $userName)
        ->where('level', 1)
        ->get();

    foreach ($approvalRoutes as $route) {
        // Send approval email to each approver
        $customerId = $customerMaster->id;
        $approverEmail = $route->email;
        $approvalName = $route->name;
        $approvalLink = url("/customer/checked/" . encrypt($customerId));

        // Log the email sending process
        Log::info("Sending email to: $approverEmail with link: $approvalLink");

        try {
            Mail::to($approverEmail)->send(new CustomerApprovalMail($customerMaster, $approvalLink, $approvalName));
        } catch (\Exception $e) {
            Log::error("Error sending email to $approverEmail: " . $e->getMessage());
        }
    }

    // Redirect or return a response as needed
    return redirect('/mst/customer')->with('status', 'success input master customer');
}

public function update($id)
{
    $id = decrypt($id);

    // Fetch dropdown data for the form
    $customerAG = Dropdown::where('category', 'Customer AG')->get();
    $types = Dropdown::where('category', 'Type')->get();
    $tax = Dropdown::where('category', 'Withholding Tax')->get();
    $title = Dropdown::where('category', 'Title')->get();

    // Fetch customer data with related logs
    $data = CustomerMaster::with(['latestChange', 'latestChange.approvalLogs.approver'])->where('id', $id)->first();
    // Check if there is an associated vendor change record
    $customerChange = CustomerChange::where('customer_id', $data->id)->first();
    // Check if the level is 8
    if ($customerChange->level != 8) {
        return redirect()->back()->with('failed', 'Data is still under approval and cannot be updated.');
    }
    // Fetch country data from API
    $response = Http::get('https://restcountries.com/v3.1/all');
    $countries = $response->json();

    // Sort countries alphabetically by name
    usort($countries, function($a, $b) {
        return strcasecmp($a['name']['common'], $b['name']['common']);
    });

    // Fetch currencies from API
    $currencyResponse = Http::get('https://openexchangerates.org/api/currencies.json?app_id=YOUR_API_KEY');
    $currencies = $currencyResponse->json();

    // Convert the currencies object to an array of [code, name] pairs
    $currencyArray = [];
    foreach ($currencies as $code => $name) {
        $currencyArray[] = ['code' => $code, 'name' => $name];
    }

    // Sort the array alphabetically by currency name
    usort($currencyArray, function($a, $b) {
        return strcasecmp($a['name'], $b['name']);
    });



    // Convert the account_group string to an array
    $data->account_group = explode(',', $data->account_group);

    return view('customer.update', compact('data', 'customerAG', 'tax', 'types', 'title', 'countries', 'currencyArray'));
}

public function detail($id)
{
    $id = decrypt($id);
    $customerAG = Dropdown::where('category', 'Customer AG')->get();
    $types = Dropdown::where('category', 'Type')->get();
    $tax = Dropdown::where('category', 'Withholding Tax')->get();
    $title = Dropdown::where('category', 'Title')->get();
    $response = Http::get('https://restcountries.com/v3.1/all');
    $countries = $response->json();
    $data = CustomerMaster::with(['latestChange', 'latestChange.logs.approver'])->where('id', $id)->first();

    // Sort countries alphabetically by name
    usort($countries, function($a, $b) {
        return strcasecmp($a['name']['common'], $b['name']['common']);
    });

    // Fetch currencies from API
    $currencyResponse = Http::get('https://openexchangerates.org/api/currencies.json?app_id=YOUR_API_KEY');
    $currencies = $currencyResponse->json();

    // Convert the currencies object to an array of [code, name] pairs
    $currencyArray = [];
    foreach ($currencies as $code => $name) {
        $currencyArray[] = ['code' => $code, 'name' => $name];
    }

    // Sort the array alphabetically by currency name
    usort($currencyArray, function($a, $b) {
        return strcasecmp($a['name'], $b['name']);
    });

    // Convert the account_group string to an array
    $data->account_group = explode(',', $data->account_group);

    return view('customer.detail', compact('data', 'customerAG', 'tax', 'types', 'title', 'countries', 'currencyArray'));
}


    public function storeUpdate(Request $request)
    {

        // Validate the incoming request data
        $validatedData = $request->validate([
            'id' => 'required|integer|exists:customer_masters,id',
            'customer_account_number' => 'nullable|string|max:255',
            'company_code' => 'required|string|max:255',
            'account_group' => 'nullable|array',
            'account_group.*' => 'nullable|string|max:255',
            'customer_name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'search_term_1' => 'nullable|string|max:255',
            'search_term_2' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'house_number' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'po_box' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'fax' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'tax_code' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:255',
            'bank_key' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'account_holder' => 'nullable|string|max:255',
            'bank_region' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:45',
            'recon_account' => 'nullable|string|max:255',
            'sort_key' => 'nullable|string|max:255',
            'cash_management_group' => 'nullable|string|max:255',
            'payment_terms' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|max:255',
            'change_type' => 'required|string|in:Create,Block,Update,Change',
            'previous_sap_customernumber' => 'nullable|string|max:255',
            'Remarks' => 'nullable|string',
            'withholding_tax' => 'nullable|array',
            'withholding_tax.*' => 'nullable|string|max:255',
            'payment_block' => 'nullable|string|in:on',
            'confirm_with' => 'nullable|string|max:45',
            'email_no_handphone' => 'nullable|string|max:45',
            'date' => 'required|date',
            'confirmed_by' => 'nullable|string|max:45',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Extract and prepare account_group and withholding_tax for saving as pipe-separated strings
        $accountGroup = isset($validatedData['account_group']) ? implode(',', $validatedData['account_group']) : null;
        $withholdingTax = isset($validatedData['withholding_tax']) ? implode('|', $validatedData['withholding_tax']) : null;

        // Handle the payment_block checkbox
        $paymentBlock = isset($validatedData['payment_block']) ? 1 : 0;

        // Assign email_no_handphone to confirm_info
        $confirmInfo = $validatedData['email_no_handphone'];

        // Remove account_group, withholding_tax, payment_block, and email_no_handphone from validated data
        unset($validatedData['account_group']);
        unset($validatedData['withholding_tax']);
        unset($validatedData['payment_block']);
        unset($validatedData['email_no_handphone']);

        // Find the existing customer record
        $customerMaster = CustomerMaster::findOrFail($validatedData['id']);

        // Handle file upload if exists
        if ($request->hasFile('file')) {
            // Delete the old file if exists
            if ($customerMaster->file && file_exists(public_path($customerMaster->file))) {
                unlink(public_path($customerMaster->file));
            }

            // Move the uploaded file to public/assets/upload directory
            $file = $request->file('file');
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/upload');
            $file->move($destinationPath, $fileName);

            // Add file path to validated data for database storage
            $validatedData['file'] = 'assets/upload/' . $fileName;
        } else {
            // Keep the old file path if no new file uploaded
            $validatedData['file'] = $customerMaster->file;
        }

        // Update the customer record in customer_masters table
        $customerMaster->update(array_merge($validatedData, [
            'account_group' => $accountGroup,
            'withholding_tax' => $withholdingTax,
            'payment_block' => $paymentBlock,
            'confirm_info' => $confirmInfo,
        ]));

        // Update the existing customer change record
        $customerChange = CustomerChange::where('customer_id', $customerMaster->id)->firstOrFail();
        $customerChange->update([
            'change_type' => $validatedData['change_type'],
            'previous_sap_customer_number' => $validatedData['previous_sap_customernumber'],
            'remarks' => $validatedData['Remarks'] ?? '',
            'status' => 'Pending', // Set initial status as pending
            'created_by' => Auth::id(), // Assuming you have authentication and get the current user's ID
            'level' => 1,
        ]);

        // Create a new approval log entry in approval_log_customer table
        $approvalLog = new ApprovalLogCustomer();
        $approvalLog->customer_change_id = $customerChange->id;
        $approvalLog->approver_id = Auth::id();
        $approvalLog->approval_action = 'Update';
        $approvalLog->approval_comments = 'Customer information updated';
        $approvalLog->approval_timestamp = now()->toDateTimeString(); // Current timestamp
        $approvalLog->approval_level = 0;
        $approvalLog->save();

        // Handle approval routing
        $user = Auth::user();
        $userDept = $user->dept;
        $userName = $user->username;

        // Find approvers based on dept and requester
        $approvalRoutes = ApprovalRoute::where('dept', $userDept)
            ->where('requester', $userName)
            ->where('level', 1)
            ->get();
        foreach ($approvalRoutes as $route) {
            // Send approval email to each approver
            $customerId = $customerMaster->id;
            $approverEmail = $route->email;
            $approvalName = $route->name;
            $approvalLink = url("/customer/checked/" . encrypt($customerId));

            // Send email
            Mail::to($approverEmail)->send(new CustomerApprovalMail($customerMaster, $approvalLink, $approvalName));
        }

        // Redirect or return a response as needed
        return redirect('/mst/customer')->with('status', 'Customer updated successfully');
    }

    public function checked($id)
{
    $id = decrypt($id);
    $customerAG = Dropdown::where('category', 'Customer AG')->get();
    $types = Dropdown::where('category', 'Type')->get();
    $tax = Dropdown::where('category', 'Withholding Tax')->get();
    $title = Dropdown::where('category', 'Title')->get();
    $response = Http::get('https://restcountries.com/v3.1/all');
    $countries = $response->json();
    $data = CustomerMaster::with(['latestChange', 'latestChange.logs.approver'])->where('id', $id)->first();

    // Sort countries alphabetically by name
    usort($countries, function ($a, $b) {
        return strcasecmp($a['name']['common'], $b['name']['common']);
    });

    $latestLevel = CustomerChange::where('customer_id', $id)->first()->level;

    // Fetch currencies from API
    $currencyResponse = Http::get('https://openexchangerates.org/api/currencies.json?app_id=YOUR_API_KEY');
    $currencies = $currencyResponse->json();

    // Convert the currencies object to an array of [code, name] pairs
    $currencyArray = [];
    foreach ($currencies as $code => $name) {
        $currencyArray[] = ['code' => $code, 'name' => $name];
    }

    // Sort the array alphabetically by currency name
    usort($currencyArray, function ($a, $b) {
        return strcasecmp($a['name'], $b['name']);
    });

    // Convert the account_group string to an array
    $data->account_group = explode(',', $data->account_group);

    // Get the current user's level
    $currentUserLevel = Auth::user()->level;

    return view('customer.checked', compact('data', 'customerAG', 'tax', 'types', 'title', 'countries', 'currencyArray', 'latestLevel', 'currentUserLevel'));
}


    public function approval(Request $request)
{
    // Validate the request if needed
    $request->validate([
        'id' => 'required|integer', // Ensure id is provided and is an integer
        'action' => 'required|in:remand,checked', // Ensure action is either 'remand' or 'checked'
        'remarks' => 'nullable|string', // Remarks are optional
        'remand_to' => 'nullable|integer|exists:users,id' // Validate remand_to if present
    ]);

    // Get the authenticated user's ID and level
    $approverId = Auth::id();
    $levelUser = Auth::user()->level;

    // Fetch the customer change record from customer_changes table
    $customerChange = CustomerChange::where('customer_id', $request->id)->first();

    // Ensure the customer change record exists
    if (!$customerChange) {
        return redirect()->back()->with('error', 'Customer change record not found.');
    }

    // Fetch the latest approval log for the customer change
    $latestApproval = ApprovalLogCustomer::where('customer_change_id', $customerChange->id)
        ->latest('approval_timestamp') // Order by 'approval_timestamp' column in descending order
        ->first();

    // Get the approver's name if the latest approval exists
    $approverName = $latestApproval && $latestApproval->approver ? $latestApproval->approver->name : 'Unknown Approver';

    // Check if the customer change has already been approved by the current level
    if ($latestApproval && $latestApproval->approval_level === $customerChange->level) {
        return redirect()->back()->with('failed', 'Master Has Been ' . $latestApproval->approval_action . ' by ' . $approverName);
    } elseif ($customerChange->level > $levelUser) {
        return redirect()->back()->with('failed', 'Master Has Been ' . $latestApproval->approval_action . ' by ' . $approverName);
    } elseif ($customerChange->level != $levelUser) {
        return redirect()->back()->with('failed', 'Master Has Been ' . $latestApproval->approval_action . ' by ' . $approverName);
    }

    // Determine the next approval level
    if ($request->action === 'checked') {
        $nextApprovalLevel = $customerChange->level + 1;
        $latestApproval->approval_level = $customerChange->level;
    } elseif ($request->action === 'remand') {
        if ($request->has('remand_to')) {
            // Fetch the user level of the remand_to user
            $remandToUser = User::findOrFail($request->remand_to);
            $nextApprovalLevel = $remandToUser->level;
        } else {
            $nextApprovalLevel = $customerChange->level - 1;
        }
        $latestApproval->approval_level = $customerChange->level;
    } else {
        // Handle other cases if needed
        $nextApprovalLevel = $customerChange->level; // Default to current level
    }

    // Fetch the user's department
    $userDept = Auth::user()->dept; // User department should be based on the submitter, not the approver
    $userName = Auth::user()->username;

    // Determine the next approval route
    if ($customerChange->level < 1) {
        $approvalRoute = ApprovalRoute::where('dept', $userDept)
            ->where('requester', $userName)
            ->where('level', $nextApprovalLevel)->get();
    } elseif ($customerChange->level < 2) {
        $approvalRoute = ApprovalRoute::where('dept', $userDept)
            ->where('level', $nextApprovalLevel)->get();
    } else {
        $approvalRoute = ApprovalRoute::where('level', $nextApprovalLevel)->get();
    }

    // Update the customer change level
    $customerChange->level = $nextApprovalLevel;
    $customerChange->status = $request->action; // Assuming 'status' is the field to update
    $customerChange->save();

    // Create a new approval log entry in approval_log_customers table
    $approvalLog = new ApprovalLogCustomer();
    $approvalLog->customer_change_id = $customerChange->id;
    $approvalLog->approver_id = $approverId;
    $approvalLog->approval_action = $request->action;
    $approvalLog->approval_comments = $request->remarks;
    $approvalLog->approval_timestamp = now()->toDateTimeString(); // Current timestamp
    $approvalLog->approval_level = $latestApproval->approval_level; // Use the updated approval level
    $approvalLog->save();

    // Fetch the customer master record
    $customerMaster = CustomerMaster::where('id', $request->id)->first();

    // If the action is remand, send email to the specified user or previous approver
    if ($request->action === 'remand') {
        if ($request->has('remand_to')) {
            // Send remand email to the specified user
            $remandToUser = User::findOrFail($request->remand_to);
            $remandEmail = $remandToUser->email;
            $remandName = $remandToUser->name;
            $remandLink = $remandToUser->level == 0 ? url("/customer/update/" . encrypt($customerMaster->id)) : url("/customer/checked/" . encrypt($customerMaster->id));

            // Send remand email
            Mail::to($remandEmail)->send(new CustomerRemandMail($customerMaster, $remandLink, $remandName, $request->remarks));
        } else {
            // Fetch the approval log for the previous level
            $previousApproval = ApprovalLogCustomer::where('customer_change_id', $customerChange->id)
                ->where('approval_level', $customerChange->level)
                ->latest('approval_timestamp')
                ->first();

            if ($previousApproval) {
                $previousApprover = User::find($previousApproval->approver_id);
                if ($previousApprover) {
                    $remandEmail = $previousApprover->email;
                    $remandName = $previousApprover->name;
                    $remandLink = $previousApproval->approval_level == 0 ? url("/customer/update/" . encrypt($customerMaster->id)) : url("/customer/checked/" . encrypt($customerMaster->id));

                    // Send remand email
                    Mail::to($remandEmail)->send(new CustomerRemandMail($customerMaster, $remandLink, $remandName, $request->remarks));
                }
            }
        }
    } else {
        // If the next level is 8, mark as completed and notify
        if ($nextApprovalLevel == 8) {
            // Mark as completed
            $customerChange->status = 'completed';
            $customerChange->save();

            // Notify level 7 user and the submitter
            $this->notifyCompletion($customerChange, $customerMaster, $userDept, $userName);

            // Generate and send PDF
            $this->generateAndSendPDF($customerChange, $customerMaster, $userDept, $userName);
        } else {
            // Send approval email to each approver in the route
            foreach ($approvalRoute as $route) {
                $approverEmail = $route->email;
                $approvalName = $route->name;
                $approvalLink = url("/customer/checked/" . encrypt($customerMaster->id));

                // Send email
                Mail::to($approverEmail)->send(new CustomerApprovalMail($customerMaster, $approvalLink, $approvalName));
            }
        }
    }

    // Redirect or return response as needed
    if ($request->action === 'remand') {
        return redirect()->back()->with('status', 'Remand processed successfully.');
    } else {
        if (isset($request->customer_account_number)) {
            $customerMaster = CustomerMaster::where('id', $request->id)->first();
            if ($customerMaster) {
                $customerMaster->update(['customer_account_number' => $request->customer_account_number]);
            }
        }
        return redirect()->back()->with('status', 'Approval processed successfully.');
    }
}


    private function notifyCompletion($customerChange, $customerMaster, $userDept, $userName)
    {
        // Fetch level 7 users
        $level7Users = User::where('level', 7)->get();
        $submitter = User::find($customerChange->created_by);

        foreach ($level7Users as $user) {
            Mail::to($user->email)->send(new CustomerCompletionMail($customerMaster, $user->name));
        }

        if ($submitter) {
            Mail::to($submitter->email)->send(new CustomerCompletionMail($customerMaster, $submitter->name));
        }
    }

    private function generateAndSendPDF($customerChange, $customerMaster, $userDept, $userName) {
        try {
            // Fetch approval logs
            $approvalLogs = ApprovalLogCustomer::where('customer_change_id', $customerChange->id)->with('approver')->get();

            // Generate the PDF content
            $pdf = PDF::loadView('customer.pdf', compact('customerChange', 'customerMaster', 'userDept', 'userName', 'approvalLogs'));
            $output = $pdf->output();

            // Define the path where the PDF will be saved
            $pdfDirectory = public_path('pdf');
            $pdfPath = $pdfDirectory . '/' . $customerMaster->id . '.pdf';

            // Ensure the directory exists
            if (!file_exists($pdfDirectory)) {
                mkdir($pdfDirectory, 0755, true);
            }

            // Save the PDF file to the specified location
            file_put_contents($pdfPath, $output);

            // Retrieve the submitter's email address
            $submitter = User::find($customerChange->created_by);
            if ($submitter) {
                $submitterEmail = $submitter->email;

                // Send the email with the PDF attached
                Mail::to($submitterEmail)->send(new CustomerFormCompleted($customerMaster, $customerChange, $pdfPath, $userName));
            } else {
                Log::error('Submitter not found for customer change ID: ' . $customerChange->id);
            }

        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error sending email: ' . $e->getMessage());
        }
    }
}
