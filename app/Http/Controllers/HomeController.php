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
    // Step 1: Fetch vendor changes that match the current user's level
    $pendingList = VendorChange::with(['vendor', 'logs.approver'])
        ->where('level', auth()->user()->level)
        ->get();

    // Step 2: Fetch the requesters (usernames) from ApprovalRoute where the current user is an approver
    $approvalRouteRequesters = ApprovalRoute::where('name', auth()->user()->username)
        ->pluck('requester');

    // Step 3: Filter pendingList where created_by matches the id of users whose usernames are in approvalRouteRequesters
    $pendingList = VendorChange::with(['vendor', 'logs.approver'])
        ->where('level', auth()->user()->level)
        ->whereHas('vendor', function ($query) use ($approvalRouteRequesters) {
            $query->whereIn('created_by', function ($query) use ($approvalRouteRequesters) {
                $query->select('id')
                      ->from('users')
                      ->whereIn('username', $approvalRouteRequesters);
            });
        })
        ->get();

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
