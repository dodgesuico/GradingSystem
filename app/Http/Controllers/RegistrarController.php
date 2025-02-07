<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;

class RegistrarController extends Controller
{
    public function index(){
        return view("registrar.registrar_dashboard");
    }

    public function registrar_classes(){
        $classes = Classes::all();
        $instructors = User::where('role', 'instructor')->get(); // Fetch all class data
        return view("registrar.registrar_classes", compact('classes'), compact('instructors'));
    }

    public function CreateClass(Request $request){
        $request->validate([
            "subject_code" => "required",
            "descriptive_title" => "required",
            "instructor" => "required",
            "academic_period" => "required",
            "schedule" => "required",
            "status" => "required",
        ]); 

        $class = new Classes();
        $class->subject_code = $request->subject_code;
        $class->descriptive_title = $request->descriptive_title;
        $class->instructor = $request->instructor;
        $class->academic_period = $request->academic_period;
        $class->schedule = $request->schedule;
        $class->status = $request->status;

        if($class->save()){
            return redirect(route("registrar_classes"))->with("success", "Class Created Successfully");
        }

        return redirect(route("registrar_classes"))->with("error", "Class Creation Failed");
    }

    public function EditClass(Request $request, Classes $class){
        $request->validate([
            "subject_code" => "required",
            "descriptive_title" => "required",
            "instructor" => "required",
            "academic_period" => "required",
            "schedule" => "required",
            "status" => "required",
        ]);

        $class->subject_code = $request->subject_code;
        $class->descriptive_title = $request->descriptive_title;
        $class->instructor = $request->instructor;
        $class->academic_period = $request->academic_period;
        $class->schedule = $request->schedule;
        $class->status = $request->status;

        if($class->save()){
            return redirect(route("registrar_classes"))->with("success", "Class Created Successfully");
        }

        return redirect(route("registrar_classes"))->with("error", "Class Creation Failed");
    }

    
}
