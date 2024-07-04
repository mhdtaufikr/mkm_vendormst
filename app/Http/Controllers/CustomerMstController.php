<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerMaster;
use App\Models\Dropdown;

class CustomerMstController extends Controller
{

    public function index(){
        // Fetch all vendor masters with their current status
        $item = CustomerMaster::get();
        $dropdown = Dropdown::where('category', 'Form')
                    ->orderBy('name_value', 'asc')
                    ->get();

        return view('master.list', compact('item','dropdown'));
    }

    public function form(){
        $customerAG = Dropdown::where('category','Customer AG')->get();
        $tax = Dropdown::where('category','Withholding Tax')->get();
        return view('form.mstcustomer',compact('customerAG','tax'));
    }


    public function store(Request $request)
    {
        // Validate $request if needed

        // Create a new CustomerMaster instance
        $customer = new CustomerMaster();

        // Assign values from $request to the model properties
        $customer->customer_account_number = $request->customer_account_number;
        $customer->company_code = $request->company_code;
        $customer->account_group = json_encode($request->account_group); // Assuming account_group is an array
        $customer->customer_name = $request->customer_name;
        $customer->title = $request->title;
        $customer->name = $request->name;
        $customer->search_term_1 = $request->search_term_1;
        $customer->search_term_2 = $request->search_term_2;
        $customer->street = $request->street;
        $customer->house_number = $request->house_number;
        $customer->postal_code = $request->postal_code;
        $customer->city = $request->city;
        $customer->country = $request->country;
        $customer->region = $request->region;
        $customer->po_box = $request->po_box;
        $customer->telephone = $request->telephone;
        $customer->fax = $request->fax;
        $customer->email = $request->email;
        $customer->tax_code = $request->tax_code;
        $customer->npwp = $request->npwp;
        $customer->bank_key = $request->bank_key;
        $customer->bank_account = $request->bank_account;
        $customer->account_holder = $request->account_holder;
        $customer->bank_region = $request->bank_region;
        $customer->recon_account = $request->recon_account;
        $customer->sort_key = $request->sort_key;
        $customer->cash_management_group = $request->cash_management_group;
        $customer->payment_terms = $request->payment_terms;
        $customer->payment_method = $request->payment_method;
        $customer->withholding_tax = json_encode($request->withholding_tax); // Assuming withholding_tax is an array

        // Save the model to the database
        $customer->save();

        // Redirect back to the specified URL with a success message
        return redirect('/form/list')->with('status', 'success input master customer');
    }
}
