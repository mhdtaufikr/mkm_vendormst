<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\VendorMaster;
use App\Models\Dropdown;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index');

    }

    public function list(){
          // Fetch all vendor masters with their current status
          $item = VendorMaster::with('currentStatus')->get();
          $dropdown = Dropdown::where('category', 'Form')
                      ->orderBy('name_value', 'asc')
                      ->get();

          return view('master.list', compact('item','dropdown'));
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
