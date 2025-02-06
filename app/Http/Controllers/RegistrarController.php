<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegistrarController extends Controller
{
    public function index(){
        return view("registrar.registrar_dashboard");
    }

    public function registrar_classes(){
        return view("registrar.registrar_classes");
    }
}
