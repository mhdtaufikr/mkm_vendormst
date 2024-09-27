<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\CustomerMaster;
use App\Models\Dropdown;
use App\Models\VendorChange;

class HomeController extends Controller
{
    public function index()
{
    // Get the current logged-in user's level
    $userLevel = auth()->user()->level;
    // Fetch vendor changes that match the user's level
    $pendingList = VendorChange::with(['vendor', 'logs.approver'])
                                ->where('level', auth()->user()->level) // assuming you are fetching pending based on user level
                                ->get();
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
