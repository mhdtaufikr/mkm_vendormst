<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorMaster;
use App\Models\Dropdown;
use App\Models\ApprovalLogVendor;
use Illuminate\Support\Facades\Http;
use App\Models\VendorChange;
use Illuminate\Support\Facades\Auth;
use App\Models\ApprovalRoute;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\VendorApprovalMail;
use App\Mail\VendorCompletionMail;
use App\Mail\VendorFormPDFMail;
use App\Mail\VendorRemandMail;
use App\Mail\VendorFormCompleted;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\Log;

class VendorMstController extends Controller
{
    public function index() {
        // Fetch all vendor masters with their current status
        $item = VendorMaster::with(['vendorChanges.logs.approver'])->get();
        $dropdown = Dropdown::where('category', 'Form')
                    ->orderBy('name_value', 'asc')
                    ->get();

        return view('vendor.list', compact('item', 'dropdown'));
    }
    public function form() {
        $vendorAG = Dropdown::where('category', 'Vendor AG')->get();
        $types = Dropdown::where('category', 'Type')->get();
        $tax = Dropdown::where('category', 'Withholding Tax')->get();
        $title = Dropdown::where('category', 'Title')->get();
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

        return view('form.mstvendor', compact('vendorAG', 'tax', 'types', 'title', 'countries', 'currencyArray'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'company_code' => 'required|string|max:255',
            'account_group' => 'nullable|array',
            'account_group.*' => 'nullable|string|max:255',
            'vendor_name' => 'nullable|string|max:255',
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
            'previous_sap_vendornumber' => 'nullable|string|max:255',
            'Remarks' => 'nullable|string',
            'withholding_tax' => 'nullable|array',
            'withholding_tax.*' => 'nullable|string|max:255',
            'payment_block' => 'nullable|string|in:on',
            'cash_management_group' => 'nullable|string|max:255',
            'payment_terms' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|max:255',
            'sort_key' => 'nullable|string|max:255',
        ]);

        // Handle file upload if exists
        if ($request->hasFile('file')) {
            // Move the uploaded file to public/assets/upload directory
            $file = $request->file('file');
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/upload');
            $file->move($destinationPath, $fileName);

            // Add file path to validated data for database storage
            $validatedData['file'] = 'assets/upload/' . $fileName;
        }

        // Extract and prepare account_group and withholding_tax for saving as pipe-separated strings
        $accountGroup = isset($validatedData['account_group']) ? implode(',', $validatedData['account_group']) : null;
        $withholdingTax = isset($validatedData['withholding_tax']) ? implode('|', $validatedData['withholding_tax']) : null;

        // Handle the payment_block checkbox
        $paymentBlock = isset($validatedData['payment_block']) ? 1 : 0;

        // Create a new vendor record in vendor_masters table
        $vendorMaster = VendorMaster::create(array_merge($validatedData, [
            'account_group' => $accountGroup,
            'withholding_tax' => $withholdingTax,
            'payment_block' => $paymentBlock,
            'confirm_info' => $request->email_no_handphone,
            'confirm_by' => $request->confirm_with,
        ]));

        // Handle vendor changes if necessary
        if (in_array($validatedData['change_type'], ['Create', 'Block', 'Update'])) {
            $vendorChangeData = [
                'vendor_id' => $vendorMaster->id,
                'change_type' => $validatedData['change_type'],
                'previous_sap_vendor_number' => $validatedData['previous_sap_vendornumber'],
                'remarks' => $validatedData['Remarks'],
                'status' => 'Pending', // Set initial status as pending
                'created_by' => Auth::id(), // Assuming you have authentication and get the current user's ID
                'level' => 1,
            ];

            // Create a new vendor change record
            VendorChange::create($vendorChangeData);
        }

        $vendorChange = VendorChange::where('vendor_id', $vendorMaster->id)->first();

        // Create a new approval log entry in approval_log_vendor table
        $approvalLog = new ApprovalLogVendor();
        $approvalLog->vendor_change_id = $vendorChange->id;
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
            $vendorId = $vendorMaster->id;
            $approverEmail = $route->email;
            $approvalName = $route->name;
            $approvalLink = url("/vendor/checked/" . encrypt($vendorId));

            // Send email
            Mail::to($approverEmail)->send(new VendorApprovalMail($vendorMaster, $approvalLink, $approvalName));
        }

        // Redirect or return a response as needed
        return redirect('/mst/vendor')->with('status', 'success input master vendor');
    }





public function update($id){
    $id = decrypt($id);
    $vendorAG = Dropdown::where('category', 'Vendor AG')->get();
    $types = Dropdown::where('category', 'Type')->get();
    $tax = Dropdown::where('category', 'Withholding Tax')->get();
    $title = Dropdown::where('category', 'Title')->get();
    $response = Http::get('https://restcountries.com/v3.1/all');
    $countries = $response->json();
    $data = VendorMaster::with(['latestChange', 'latestChange.logs.approver'])->where('id', $id)->first();


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

    return view('vendor.update', compact('data','vendorAG', 'tax', 'types', 'title', 'countries', 'currencyArray'));
}

    public function detail($id)
{
    $id = decrypt($id);
    $vendorAG = Dropdown::where('category', 'Vendor AG')->get();
    $types = Dropdown::where('category', 'Type')->get();
    $tax = Dropdown::where('category', 'Withholding Tax')->get();
    $title = Dropdown::where('category', 'Title')->get();
    $response = Http::get('https://restcountries.com/v3.1/all');
    $countries = $response->json();
    $data = VendorMaster::with(['latestChange', 'latestChange.logs.approver'])->where('id', $id)->first();

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

    return view('vendor.detail', compact('data','vendorAG', 'tax', 'types', 'title', 'countries', 'currencyArray'));
}

public function storeUpdate(Request $request)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'id' => 'required|integer|exists:vendor_masters,id',
        'vendor_account_number' => 'nullable|string|max:255',
        'company_code' => 'required|string|max:255',
        'account_group' => 'nullable|array',
        'account_group.*' => 'nullable|string|max:255',
        'vendor_name' => 'nullable|string|max:255',
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
        'previous_sap_vendornumber' => 'nullable|string|max:255',
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

    // Find the existing vendor record
    $vendorMaster = VendorMaster::findOrFail($validatedData['id']);

    // Handle file upload if exists
    if ($request->hasFile('file')) {
        // Delete the old file if exists
        if ($vendorMaster->file && file_exists(public_path($vendorMaster->file))) {
            unlink(public_path($vendorMaster->file));
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
        $validatedData['file'] = $vendorMaster->file;
    }

    // Update the vendor record in vendor_masters table
    $vendorMaster->update(array_merge($validatedData, [
        'account_group' => $accountGroup,
        'withholding_tax' => $withholdingTax,
        'payment_block' => $paymentBlock,
        'confirm_info' => $confirmInfo,
    ]));

    // Update the existing vendor change record
    $vendorChange = VendorChange::where('vendor_id', $vendorMaster->id)->firstOrFail();
    $vendorChange->update([
        'change_type' => $validatedData['change_type'],
        'previous_sap_vendor_number' => $validatedData['previous_sap_vendornumber'],
        'remarks' => $validatedData['Remarks'] ?? '',
        'status' => 'Pending', // Set initial status as pending
        'created_by' => Auth::id(), // Assuming you have authentication and get the current user's ID
        'level' => 1,
    ]);

    // Create a new approval log entry in approval_log_vendor table
    $approvalLog = new ApprovalLogVendor();
    $approvalLog->vendor_change_id = $vendorChange->id;
    $approvalLog->approver_id = Auth::id();
    $approvalLog->approval_action = 'Update';
    $approvalLog->approval_comments = 'Vendor information updated';
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
        $vendorId = $vendorMaster->id;
        $approverEmail = $route->email;
        $approvalName = $route->name;
        $approvalLink = url("/vendor/checked/" . encrypt($vendorId));

        // Send email
        Mail::to($approverEmail)->send(new VendorApprovalMail($vendorMaster, $approvalLink, $approvalName));
    }

    // Redirect or return a response as needed
    return redirect('/mst/vendor')->with('status', 'Vendor updated successfully');
}




public function checked($id)
{
    $id = decrypt($id);
    $vendorAG = Dropdown::where('category', 'Vendor AG')->get();
    $types = Dropdown::where('category', 'Type')->get();
    $tax = Dropdown::where('category', 'Withholding Tax')->get();
    $title = Dropdown::where('category', 'Title')->get();
    $response = Http::get('https://restcountries.com/v3.1/all');
    $countries = $response->json();
    $data = VendorMaster::with(['latestChange', 'latestChange.logs.approver'])->where('id', $id)->first();

    // Sort countries alphabetically by name
    usort($countries, function($a, $b) {
        return strcasecmp($a['name']['common'], $b['name']['common']);
    });

    $latestLevel = VendorChange::where('vendor_id', $id)->first()->level;

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

    // Get the current user's level
    $currentUserLevel = Auth::user()->level;

    return view('vendor.checked', compact('data', 'vendorAG', 'tax', 'types', 'title', 'countries', 'currencyArray', 'latestLevel', 'currentUserLevel'));
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

    // Fetch the vendor change record from vendor_changes table
    $vendorChange = VendorChange::where('vendor_id', $request->id)->first();

    // Ensure the vendor change record exists
    if (!$vendorChange) {
        return redirect()->back()->with('error', 'Vendor change record not found.');
    }

    // Fetch the latest approval log for the vendor change
    $latestApproval = ApprovalLogVendor::where('vendor_change_id', $vendorChange->id)
        ->latest('approval_timestamp') // Order by 'approval_timestamp' column in descending order
        ->first();

    // Get the approver's name if the latest approval exists
    $approverName = $latestApproval && $latestApproval->approver ? $latestApproval->approver->name : 'Unknown Approver';

    // Check if the vendor change has already been approved by the current level
    if ($latestApproval && $latestApproval->approval_level === $vendorChange->level) {
        return redirect()->back()->with('failed', 'Master Has Been ' . $latestApproval->approval_action . ' by ' . $approverName);
    } elseif ($vendorChange->level > $levelUser) {
        return redirect()->back()->with('failed', 'Master Has Been ' . $latestApproval->approval_action . ' by ' . $approverName);
    } elseif ($vendorChange->level != $levelUser) {
        return redirect()->back()->with('failed', 'Master Has Been ' . $latestApproval->approval_action . ' by ' . $approverName);
    }

    // Determine the next approval level
    if ($request->action === 'checked') {
        $nextApprovalLevel = $vendorChange->level + 1;
        $latestApproval->approval_level = $vendorChange->level;
    } elseif ($request->action === 'remand') {
        if ($request->has('remand_to')) {
            // Fetch the user level of the remand_to user
            $remandToUser = User::findOrFail($request->remand_to);
            $nextApprovalLevel = $remandToUser->level;
        } else {
            $nextApprovalLevel = $vendorChange->level - 1;
        }
        $latestApproval->approval_level = $vendorChange->level;
    } else {
        // Handle other cases if needed
        $nextApprovalLevel = $vendorChange->level; // Default to current level
    }

    // Fetch the user's department
    $userDept = Auth::user()->dept; // User department should be based on the submitter, not the approver
    $userName = Auth::user()->username;

    // Determine the next approval route
    if ($vendorChange->level < 1) {
        $approvalRoute = ApprovalRoute::where('dept', $userDept)
            ->where('requester', $userName)
            ->where('level', $nextApprovalLevel)->get();
    } elseif ($vendorChange->level < 2) {
        $approvalRoute = ApprovalRoute::where('dept', $userDept)
            ->where('level', $nextApprovalLevel)->get();
    } else {
        $approvalRoute = ApprovalRoute::where('level', $nextApprovalLevel)->get();
    }

    // Update the vendor change level
    $vendorChange->level = $nextApprovalLevel;
    $vendorChange->status = $request->action; // Assuming 'status' is the field to update
    $vendorChange->save();

    // Create a new approval log entry in approval_log_vendor table
    $approvalLog = new ApprovalLogVendor();
    $approvalLog->vendor_change_id = $vendorChange->id;
    $approvalLog->approver_id = $approverId;
    $approvalLog->approval_action = $request->action;
    $approvalLog->approval_comments = $request->remarks;
    $approvalLog->approval_timestamp = now()->toDateTimeString(); // Current timestamp
    $approvalLog->approval_level = $latestApproval->approval_level; // Use the updated approval level
    $approvalLog->save();

    // Fetch the vendor master record
    $vendorMaster = VendorMaster::where('id', $request->id)->first();

    // If the action is remand, send email to the specified user or previous approver
    if ($request->action === 'remand') {
        if ($request->has('remand_to')) {
            // Send remand email to the specified user
            $remandToUser = User::findOrFail($request->remand_to);
            $remandEmail = $remandToUser->email;
            $remandName = $remandToUser->name;
            $remandLink = $remandToUser->level == 0 ? url("/vendor/update/" . encrypt($vendorMaster->id)) : url("/vendor/checked/" . encrypt($vendorMaster->id));

            // Send remand email
            Mail::to($remandEmail)->send(new VendorRemandMail($vendorMaster, $remandLink, $remandName, $request->remarks));
        } else {
            // Fetch the approval log for the previous level
            $previousApproval = ApprovalLogVendor::where('vendor_change_id', $vendorChange->id)
                ->where('approval_level', $vendorChange->level)
                ->latest('approval_timestamp')
                ->first();

            if ($previousApproval) {
                $previousApprover = User::find($previousApproval->approver_id);
                if ($previousApprover) {
                    $remandEmail = $previousApprover->email;
                    $remandName = $previousApprover->name;
                    $remandLink = $previousApproval->approval_level == 0 ? url("/vendor/update/" . encrypt($vendorMaster->id)) : url("/vendor/checked/" . encrypt($vendorMaster->id));

                    // Send remand email
                    Mail::to($remandEmail)->send(new VendorRemandMail($vendorMaster, $remandLink, $remandName, $request->remarks));
                }
            }
        }
    } else {
        // If the next level is 8, mark as completed and notify
        if ($nextApprovalLevel == 8) {
            // Mark as completed
            $vendorChange->status = 'completed';
            $vendorChange->save();

            // Notify level 7 user and the submitter
            /* $this->notifyCompletion($vendorChange, $vendorMaster, $userDept, $userName); */

            // Generate and send PDF
            $this->generateAndSendPDF($vendorChange, $vendorMaster, $userDept, $userName);
        } else {
            // Send approval email to each approver in the route
            foreach ($approvalRoute as $route) {
                $approverEmail = $route->email;
                $approvalName = $route->name;
                $approvalLink = url("/vendor/checked/" . encrypt($vendorMaster->id));

                // Send email
                Mail::to($approverEmail)->send(new VendorApprovalMail($vendorMaster, $approvalLink, $approvalName));
            }
        }
    }

    // Redirect or return response as needed
    if ($request->action === 'remand') {
        return redirect()->back()->with('status', 'Remand processed successfully.');
    } else {
        if (isset($request->vendor_account_number)) {
            $vendorMaster = VendorMaster::where('id', $request->id)->first();
            if ($vendorMaster) {
                $vendorMaster->update(['vendor_account_number' => $request->vendor_account_number]);
            }
        }
        return redirect()->back()->with('status', 'Approval processed successfully.');
    }
}



        private function notifyCompletion($vendorChange, $vendorMaster, $userDept, $userName) {
                // Fetch level 7 users
                $level7Users = User::where('level', 7)->get();
                $submitter = User::find($vendorChange->created_by);

                foreach ($level7Users as $user) {
                    Mail::to($user->email)->send(new VendorCompletionMail($vendorMaster, $user->name));
                }

                if ($submitter) {
                    Mail::to($submitter->email)->send(new VendorCompletionMail($vendorMaster, $submitter->name));
                }
        }

        private function generateAndSendPDF($vendorChange, $vendorMaster, $userDept, $userName) {
            try {
                // Fetch approval logs
                $approvalLogs = ApprovalLogVendor::where('vendor_change_id', $vendorChange->id)->with('approver')->get();

                // Generate the PDF content
                $pdf = PDF::loadView('vendor.pdf', compact('vendorChange', 'vendorMaster', 'userDept', 'userName', 'approvalLogs'));
                $output = $pdf->output();

                // Define the path where the PDF will be saved
                $pdfDirectory = public_path('pdf');
                $pdfPath = $pdfDirectory . '/' . $vendorMaster->vendor_account_number . '.pdf';

                // Ensure the directory exists
                if (!file_exists($pdfDirectory)) {
                    mkdir($pdfDirectory, 0755, true);
                }

                // Save the PDF file to the specified location
                file_put_contents($pdfPath, $output);

                // Retrieve the submitter's email address
                $submitter = User::find($vendorChange->created_by);
                if ($submitter) {
                    $submitterEmail = $submitter->email;

                    // Send the email with the PDF attached
                    Mail::to($submitterEmail)->send(new VendorFormCompleted($vendorMaster, $vendorChange, $pdfPath));
                } else {
                    Log::error('Submitter not found for vendor change ID: ' . $vendorChange->id);
                }

            } catch (\Exception $e) {
                // Log the error message
                Log::error('Error sending email: ' . $e->getMessage());
            }
        }

}
