<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\CustomerMaster;
use App\Models\Dropdown;
use App\Models\VendorChange;
use App\Models\CustomerChange;
use App\Models\ApprovalRoute;

class HomeController extends Controller
{
    public function index()
{
    // Get the current user's level and department
    $userLevel = auth()->user()->level;
    $userDept = auth()->user()->dept;

    // Step 1: Start the base query for vendor changes matching the user's level
    $vendorPendingList = VendorChange::with(['vendor', 'logs.approver'])
        ->where('level', $userLevel);

    $customerPendingList = CustomerChange::with(['customer', 'logs.approver'])
        ->where('level', $userLevel);

    // Step 2: Add additional filtering based on the user's level for vendor changes
    if ($userLevel == 1) {
        // Fetch the requesters (usernames) from ApprovalRoute where the current user is an approver
        $approvalRouteRequesters = ApprovalRoute::where('name', auth()->user()->username)
            ->pluck('requester');

        // Apply the filter based on requesters
        $vendorPendingList->whereHas('vendor', function ($query) use ($approvalRouteRequesters) {
            $query->whereIn('created_by', function ($subquery) use ($approvalRouteRequesters) {
                $subquery->select('id')
                    ->from('users')
                    ->whereIn('username', $approvalRouteRequesters);
            });
        });

        $customerPendingList->whereHas('customer', function ($query) use ($approvalRouteRequesters) {
            $query->whereIn('created_by', function ($subquery) use ($approvalRouteRequesters) {
                $subquery->select('id')
                    ->from('users')
                    ->whereIn('username', $approvalRouteRequesters);
            });
        });
    } elseif ($userLevel == 2) {
        // For vendor changes, filter by department
        $vendorPendingList->whereHas('vendor', function ($query) use ($userDept) {
            $query->whereIn('created_by', function ($subquery) use ($userDept) {
                // Fetch department from users based on who created the vendor change
                $subquery->select('id')
                    ->from('users')
                    ->where('dept', $userDept); // Match creator's department
            });
        });

        // For customer changes, filter by department
        $customerPendingList->whereHas('customer', function ($query) use ($userDept) {
            $query->whereIn('created_by', function ($subquery) use ($userDept) {
                $subquery->select('id')
                    ->from('users')
                    ->where('dept', $userDept);
            });
        });
    } elseif ($userLevel > 2) {
        // For levels greater than 2, no need to filter by department, just filter by level
        $vendorPendingList->where('level', $userLevel);
        $customerPendingList->where('level', $userLevel);
    }

    // Get the filtered result
    $vendorPendingList = $vendorPendingList->get();
    $customerPendingList = $customerPendingList->get();

    // Merge vendor and customer lists, and include an indicator of which list each entry belongs to
    $pendingList = collect();

    foreach ($vendorPendingList as $vendor) {
        $vendor->type = 'Supplier';  // Mark as supplier
        $pendingList->push($vendor);
    }

    foreach ($customerPendingList as $customer) {
        $customer->type = 'Customer'; // Mark as customer
        $pendingList->push($customer);
    }

    // Sort the merged list by the 'created_at' field
    $pendingList = $pendingList->sortByDesc('created_at');

    // Return the filtered list to the view
    return view('home.index', compact('pendingList'));
}








    public function form(Request $request)
    {
        $form = $request->input('form');

        // Determine which view to load based on the form value
        if ($form === 'Master Vendor') {
            return view('form.mstvendor');
        } elseif ($form === 'Master Customer') {
            $customerAG = Dropdown::where('category','Customer AG')->get();
            $tax = Dropdown::where('category','Withholding Tax')->get();
            return view('form.mstcustomer',compact('customerAG','tax'));
        } else {
            // Handle any other cases or provide a default view
            abort(404); // or redirect to an error page
        }
    }

}
