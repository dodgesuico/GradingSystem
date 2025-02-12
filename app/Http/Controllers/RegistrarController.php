<?php

namespace App\Http\Controllers;

use App\Models\Percentage;
use App\Models\QuizzesAndScores;
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
        return view('registrar.registrar_classes', compact('classes', 'instructors'));
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

        $quizzesandscores = QuizzesAndScores::where('classID', $class->id)->get();

        $percentage = Percentage::where('classID', $class->id)->first();


        return view('registrar.registrar_classes_view', compact('class', 'students', 'classes_student', 'quizzesandscores', 'percentage'));
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

        $quizzesandscores = new QuizzesAndScores();
        $quizzesandscores->classID = $class->id;
        $quizzesandscores->studentID = $request->student_id;

        // Save the instance of Classes_Student
        if ($classStudent->save() && $quizzesandscores->save()) {
            return redirect()->route("class.show", $class->id)->with("success", "Student added successfully.");
        }

        return redirect()->route("class.show",$class->id)->with("error", "Failed to add student. Please try again.");
    }

    public function removestudent($class, $student, $quizzesscores)
    {
        // Find the student in the class
        $classStudent = Classes_Student::where('classId', $class)
                                    ->where('studentID', $student)
                                    ->first();

        $quizzesscores = QuizzesAndScores::where('classID', $class)
                                    ->where('studentID', $student)
                                    ->first();


        if ($classStudent || $quizzesscores) {
            if ($classStudent) {
                $classStudent->delete();
            }

            if ($quizzesscores) {
                $quizzesscores->delete();
            }

            return redirect()->route("class.show", $class)->with("success", "Student removed successfully.");
        }

        return redirect()->route("class.show", $class)->with("error", "Student not found or already removed.");
    }

    public function addPercentageAndScores(Request $request, Classes $class)
    {
            // Validate the request
        $request->validate([
            'quiz_percentage' => 'required|integer|min:0|max:100',
            'quiz_total_score' => 'nullable|integer|min:0',
            'attendance_percentage' => 'required|integer|min:0|max:100',
            'attendance_total_score' => 'nullable|integer|min:0',
            'assignment_participation_project_percentage' => 'required|integer|min:0|max:100',
            'assignment_participation_project_total_score' => 'nullable|integer|min:0',
            'exam_percentage' => 'required|integer|min:0|max:100',
            'exam_total_score' => 'nullable|integer|min:0',
        ]);

        // Calculate the total percentage
        $totalPercentage = $request->input('quiz_percentage') +
                        $request->input('attendance_percentage') +
                        $request->input('assignment_participation_project_percentage') +
                        $request->input('exam_percentage');

        // Check if total percentage is exactly 100
        if ($totalPercentage !== 100) {
            return redirect()->route("class.show", $class)
                            ->withErrors(['The total percentage must equal 100%.']);
        }

        // Save or update the record in your `percentage` table
        Percentage::updateOrCreate(
            ['classID' => $class->id], // Condition to check if it already exists for this class
            [
                'classID' => $class->id,  // Ensure classID is set in case a new record is created
                'quiz_percentage' => $request->input('quiz_percentage'),
                'quiz_total_score' => $request->input('quiz_total_score'),
                'attendance_percentage' => $request->input('attendance_percentage'),
                'attendance_total_score' => $request->input('attendance_total_score'),
                'assignment_participation_project_percentage' => $request->input('assignment_participation_project_percentage'),
                'assignment_participation_project_total_score' => $request->input('assignment_participation_project_total_score'),
                'exam_percentage' => $request->input('exam_percentage'),
                'exam_total_score' => $request->input('exam_total_score'),
            ]
        );


        return redirect()->route("class.show", $class)->with('success', 'Data saved successfully.');
    }


}
