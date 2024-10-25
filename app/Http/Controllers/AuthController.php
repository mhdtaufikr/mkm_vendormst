<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Add this line

class AuthController extends Controller
{
      // Handle Change Password Logic
      public function changePassword(Request $request)
      {
          // Validate the request
          $request->validate([
              'old_password' => 'required',
              'new_password' => 'required|min:8|confirmed',
          ]);

          // Check if the old password is correct
          if (!Hash::check($request->old_password, Auth::user()->password)) {
              return back()->withErrors(['old_password' => 'Old password is incorrect']);
          }

          // Update the user's password
          Auth::user()->update([
              'password' => Hash::make($request->new_password),
          ]);

          // Return a success message
          return back()->with('password', 'Password changed successfully');
      }

    public function login(){
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $usernameOrEmail = $request->input('email');
        $password = $request->input('password');
        // Determine if input is likely an email address
        $isEmail = filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL);

        // Define the credentials array based on input type
        if ($isEmail) {
            $credentials = ['email' => $usernameOrEmail];
        } else {
            $credentials = ['username' => $usernameOrEmail];
        }

        $credentials['password'] = $password;

        // Attempt authentication
        if (Auth::attempt($credentials)) {
            // Authentication successful
            $user = Auth::user();

            // Check user status
            if ($user->is_active == '1') {
                // Update last login
                User::where('id', $user->id)
                    ->update([
                        'last_login' => now(),
                        'login_counter' => $user->login_counter + 1,
                    ]);

                // Redirect to the intended URL or to home page
                return redirect()->intended('/home');
            } else {
                // User is not active, redirect with message
                return redirect('/')->with('statusLogin', 'Give Access First to User');
            }
        } else {
            // Authentication failed, redirect with message
            return redirect('/')->with('statusLogin', 'Wrong Username/Email or Password');
        }
    }





    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('statusLogout','Success Logout');
    }
}
