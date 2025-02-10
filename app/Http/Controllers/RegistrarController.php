<?php

namespace App\Http\Controllers;

use App\Models\Classes_Student;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;


class RegistrarController extends Controller
{
    public function index()
    {
        return view("registrar.registrar_dashboard");
    }

    public function registrar_classes()
    {
        $classes = Classes::all();
        $instructors = User::where('role', 'instructor')->get(); // Fetch all class data
        return view("registrar.registrar_classes", compact('classes'), compact('instructors'));
    }

    public function CreateClass(Request $request)
    {
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

        if ($class->save()) {
            return redirect(route("registrar_classes"))->with("success", "Class Created Successfully");
        }

        return redirect(route("registrar_classes"))->with("error", "Class Creation Failed");
    }

    public function EditClass(Request $request, Classes $class)
    {
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

        if ($class->save()) {
            return redirect(route("registrar_classes"))->with("success", "Class Edited Successfully");
        }

        return redirect(route("registrar_classes"))->with("error", "Class Edition Failed");
    }

    public function DeleteClass(Request $request, Classes $class)
    {
        try {
            $class->delete(); // Delete the class from the database
            return redirect()->route('registrar_classes')->with('success', 'Class deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('registrar_classes')->with('error', 'Failed to delete class. Please try again.');
        }
    }

    public function show(Classes $class)
    {
        // Get all student IDs already in the class
        $enrolledStudentIds = Classes_Student::where('classId', $class->id)->pluck('studentID')->toArray();

        // Get students who are not already enrolled in the class
        $students = User::where('role', 'student')->whereNotIn('id', $enrolledStudentIds)->get();

        $classes_student = Classes_Student::where('classId', $class->id)->get();

        return view('registrar.registrar_classes_view', compact('class', 'students', 'classes_student'));
    }

    public function addstudent(Request $request, Classes $class)
    {
        $request->validate([
            "student_id" => "required",
            "name" => "required",
            "email" => "required|email",
            "department" => "required",
        ]);

        // Create a new instance of Classes_Student and assign the values
        $classStudent = new Classes_Student();
        $classStudent->classId = $class->id;  // Use the existing class ID
        $classStudent->studentID = $request->student_id;
        $classStudent->name = $request->name;
        $classStudent->email = $request->email;
        $classStudent->department = $request->department;

        // Save the instance of Classes_Student
        if ($classStudent->save()) {
            return redirect()->route("class.show", $class->id)->with("success", "Student added successfully.");
        }

        return redirect()->route("class.show",$class->id)->with("error", "Failed to add student. Please try again.");
    }

}
