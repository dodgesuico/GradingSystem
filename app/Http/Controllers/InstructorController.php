<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InstructorController extends Controller
{
    //
  public function index(){
    return view("instructor.instructor_dashboard");
  }

  public function classes(){
    return view("instructor.instructor_classes");
  }
}
