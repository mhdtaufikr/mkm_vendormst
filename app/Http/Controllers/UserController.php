<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Dropdown;


use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $user = User::get();
        $dropdown = Dropdown::where('category','Role')
        ->get();
        return view('users.index',compact('user','dropdown'));
    }


    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'username' => 'required|string|max:45',
            'name' => 'required|string|max:255',
            'level' => 'required|integer',
            'dept' => 'required|string|max:45',
            'password' => 'required|string|min:8',
            'role' => 'required|string|max:255',
            'is_active' => 'required|in:1,0',
        ]);

        // Hash the password
        $password = bcrypt($request->password);

        // Create a new user record
        $addUser = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'level' => $request->level,
            'dept' => $request->dept,
            'password' => $password,
            'role' => $request->role,
            'is_active' => $request->is_active,
            'last_login' => null,
            'login_counter' => 0,
        ]);

        // Check if the user creation was successful and redirect accordingly
        if ($addUser) {
            return redirect('/user')->with('status', 'Success Add User');
        } else {
            return redirect('/user')->with('status', 'Failed Add User');
        }
    }



    public function storePartner(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $password = bcrypt($request->password);
        //dd($password);
        $addUser=User::create([
            'id_partner' => $request->id_partner,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'role' => 'User',
            'last_login' => null,
            'is_active' => '1',

        ]);
        if ($addUser) {
            return redirect('/partner')->with('status','Success Add User');
        }else{
            return redirect('/partner')->with('status','Failed Add User');
        }
    }

    public function revoke($id)
    {
        $revoke= User::where('id',$id)
        ->update([
            'is_active' => '0',
        ]);

            return redirect('/user')->with('status','Success Revoke User');

    }
    public function access($id)
    {
        $access= User::where('id',$id)
        ->update([
            'is_active' => '1',
        ]);
            return redirect('/user')->with('status','Success Give User Access');
    }


    public function update(Request $request, $id)
{
    $request->validate([
        'username' => 'required|string|max:45',
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'password' => 'nullable|string|min:8',
        'role' => 'required|string|max:255',
        'dept' => 'required|string|max:45',
        'level' => 'required|integer',
    ]);

    // Prepare data for update
    $updateData = [
        'username' => $request->username,
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'dept' => $request->dept,
        'level' => $request->level,
    ];

    // If password is provided, hash it and include it in the update data
    if ($request->filled('password')) {
        $updateData['password'] = bcrypt($request->password);
    }

    // Perform the update
    User::where('id', $id)->update($updateData);

    return redirect('/user')->with('status', 'Success Update User');
}

}
