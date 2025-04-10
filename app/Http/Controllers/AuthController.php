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

    function LoginPost(Request $request)
    {
        $request->validate([
            "login" => "required",
            "password" => "required",
        ]);

        $login = $request->login;
        $password = $request->password;

        $user = User::where(function ($query) use ($login) {
            $query->where('email', $login)->orWhere('studentID', $login);
        })->first();

        if (!$user) {
            return back()->withErrors(['login' => 'No account found for the provided email or student ID.']);
        }

        // Check for email domain if login was via email
        if (filter_var($login, FILTER_VALIDATE_EMAIL) && !preg_match('/@ckcm\.edu\.ph$/', $login)) {
            return back()->withErrors(['login' => 'Only @ckcm.edu.ph emails are allowed.']);
        }

        if (!\Hash::check($password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        Auth::login($user);
        return redirect()->route('index')->with('success', 'Login Success');
    }


    public function register()
    {
        $departments = Department::all(); // Fetch all departments from the departments table
        return view('auth.register', compact('departments')); // Pass the departments to the register view
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
