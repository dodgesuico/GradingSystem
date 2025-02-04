<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
        return view("auth.login");
    }

    function RegisterPost(Request $resquest)
    {
        $resquest->validate([
            "email" => "required",
            "password" => "required",
        ]);

        $user = new User();
        $user->name = $resquest->fullname;
    }
}
