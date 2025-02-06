<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view("auth.login");
    }

    public function register()
    {
        $departments = Department::all(); // Fetch all departments from the departments table
        return view('auth.register', compact('departments')); // Pass the departments to the register view
    }

    function LoginPost(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);
        
        // Check if the email exists first
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'The email does not exist.']);
        }
        
        // Check if the password is correct
        if (!\Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['password' => 'The password is incorrect.']);
        }
        
        // Attempt to log in
        if (Auth::attempt($request->only('email', 'password'))) {
            if ($user->role === 'instructor') {
                return redirect(route('teacher'))->with('success', 'Welcome, Instructor!');
            }

            if ($user->role === 'registrar') {
                return redirect(route('registrar'))->with('success', 'Welcome, Registrar!');
            }

            if ($user->role === 'dean') {
                return redirect(route('dean'))->with('success', 'Welcome, Registrar!');
            }

            if ($user->role === 'admin') {
                return redirect(route('admin'))->with('success', 'Welcome, Registrar!');
            }
        
        
            return redirect(route('index'))->with('success', 'Login Success');
        }
        
        return redirect(route('login'))->withErrors(['error' => 'Login failed. Please try again.']);
      
    }

    function RegisterPost(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|email",
            "department" => "",
            "password" => "required|min:4",
            "confirm_password" => "required|same:password",
            "role" => "required",
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->department = $request->department;
        $user->password = $request->password;
        $user->role = $request->role;

        if($user->save()){
            return redirect(route("login"))->with("success", "Account Created Successfully");
        }

        return redirect(route("register"))->with("error", "Account Creation Failed");

    }
}
