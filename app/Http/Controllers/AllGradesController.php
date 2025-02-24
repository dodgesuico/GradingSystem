<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AllGradesController extends Controller
{
    public function index()
    {
        return view("registrar.allgrades");
    }
}
