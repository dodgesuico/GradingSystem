<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        return view("auth.register");
    }

    function LoginPost(Request $resquest)
    {
        $resquest->validate([
            "email" => "required",
            "password" => "required",
        ]);

        $cred = $resquest->only("email", "password");
        if(Auth::attempt($cred)){
            $user = Auth::user();

            if ($user->role === 'registrar') {
                return redirect(route('registrar'))->with('success', 'Welcome, Registrar!');
            }
            
            return redirect(route("welcome"))->with("success", "Login Success");
        }

        return redirect(route("login"))->with("error", "Login Failed");

        
      
    }

    function RegisterPost(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|email",
            "password" => "required|min:4",
            "confirm_password" => "required|same:password",
            "role" => "required",
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role = $request->role;

        if($user->save()){
            return redirect(route("login"))->with("success", "Account Created Successfully");
        }

        return redirect(route("register"))->with("error", "Account Creation Failed");

    }
}
