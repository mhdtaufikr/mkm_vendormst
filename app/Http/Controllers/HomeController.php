<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\CustomerMaster;
use App\Models\Dropdown;
use App\Models\VendorChange;
use App\Models\ApprovalRoute;

class HomeController extends Controller
{
    public function index()
{
    // Get the current user's level and department
    $userLevel = auth()->user()->level;
    $userDept = auth()->user()->dept;

    // Step 1: Start the base query for vendor changes matching the user's level
    $pendingList = VendorChange::with(['vendor', 'logs.approver'])
        ->where('level', $userLevel);

    // Step 2: Add additional filtering based on the user's level
    if ($userLevel == 1) {
        // Fetch the requesters (usernames) from ApprovalRoute where the current user is an approver
        $approvalRouteRequesters = ApprovalRoute::where('name', auth()->user()->username)
            ->pluck('requester');

        // Apply the filter based on requesters
        $pendingList->whereHas('vendor', function ($query) use ($approvalRouteRequesters) {
            $query->whereIn('created_by', function ($subquery) use ($approvalRouteRequesters) {
                $subquery->select('id')
                    ->from('users')
                    ->whereIn('username', $approvalRouteRequesters);
            });
        });
    } elseif ($userLevel == 2) {
        // Fetch vendor changes where the creator's department matches the current user's department
        $pendingList->whereHas('vendor', function ($query) use ($userDept) {
            $query->whereIn('created_by', function ($subquery) use ($userDept) {
                // Fetch department from users based on who created the vendor change
                $subquery->select('id')
                    ->from('users')
                    ->where('dept', $userDept); // Match creator's department
            });
        });
    } elseif ($userLevel > 2) {
        // For levels greater than 2, no need to filter by department, just filter by level
        $pendingList->where('level', $userLevel);
    }

    // Get the filtered result
    $pendingList = $pendingList->get();

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
