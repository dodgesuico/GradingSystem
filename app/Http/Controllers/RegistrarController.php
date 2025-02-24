<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\FinalGrade;
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

    public function show(Request $request, Classes $class)
    {

        $enrolledStudentIds = Classes_Student::where('classID', $class->id)->pluck('studentID')->toArray();

        $students = User::where('role', 'student')
            ->whereNotIn('id', $enrolledStudentIds)
            ->get();

        $classes_student = Classes_Student::where('classID', $class->id)->get();

        $quizzesandscores = QuizzesAndScores::where('classID', $class->id)->get();

        $percentage = Percentage::where('classID', $class->id)->get();

        $finalGrades = DB::table('final_grade')
            ->where('classID', $class->id)
            ->get();

        return view('registrar.registrar_classes_view', compact('class', 'students', 'classes_student', 'quizzesandscores', 'percentage', 'finalGrades'));
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
        $classStudent->classID = $class->id;
        $classStudent->studentID = $request->student_id;
        $classStudent->name = $request->name;
        $classStudent->email = $request->email;
        $classStudent->department = $request->department;

        // Array of periodic terms
        $periodicTerms = ['Prelim', 'Midterm', 'Semi-Finals', 'Finals'];

        // Save the instance of Classes_Student
        if ($classStudent->save()) {
            // Insert a row for each periodic term in quizzes_scores
            foreach ($periodicTerms as $term) {
                $quizzesandscores = new QuizzesAndScores();
                $quizzesandscores->classID = $class->id;
                $quizzesandscores->studentID = $request->student_id;
                $quizzesandscores->periodic_term = $term;
                $quizzesandscores->save();
            }

            return redirect()->route("class.show", $class->id)->with("success", "Student added successfully.");
        }

        return redirect()->route("class.show", $class->id)->with("error", "Failed to add student. Please try again.");
    }


    public function removestudent($class, $student)
    {
        // Find the student in the class
        $classStudent = Classes_Student::where('classID', $class)
            ->where('studentID', $student)
            ->first();

        // Find all related quizzes and scores for this student in the class
        $quizzesScores = QuizzesAndScores::where('classID', $class)
            ->where('studentID', $student)
            ->get();

        // Find the student's final grade in the class
        $finalGrade = FinalGrade::where('classID', $class)
            ->where('studentID', $student)
            ->first();

        if ($classStudent || $quizzesScores->isNotEmpty() || $finalGrade) {
            // Delete student from classes_student
            if ($classStudent) {
                $classStudent->delete();
            }

            // Delete related quizzes and scores
            if ($quizzesScores->isNotEmpty()) {
                foreach ($quizzesScores as $score) {
                    $score->delete();
                }
            }

            // Delete the student's final grade
            if ($finalGrade) {
                $finalGrade->delete();
            }

            return redirect()->route("class.show", $class)->with("success", "Student removed successfully.");
        }

        return redirect()->route("class.show", $class)->with("error", "Student not found or already removed.");
    }


    public function addPercentageAndScores(Request $request, Classes $class)
    {
        $periodicTerms = $request->input('periodic_terms');
        $warnings = [];

        foreach ($periodicTerms as $term) {
            // Calculate total percentage
            $totalPercentage = $request->input("quiz_percentage.$term") +
                $request->input("attendance_percentage.$term") +
                $request->input("assignment_percentage.$term") +
                $request->input("exam_percentage.$term");

            if ($totalPercentage !== 100) {
                return redirect()->route("class.show", $class)
                    ->withErrors(["The total percentage for $term must equal 100%."]);
            }

            foreach (['quiz', 'attendance', 'assignment', 'exam'] as $category) {
                $totalScore = $request->input("{$category}_total_score.$term");

                // Check if this total score exists in transmuted_grade
                $scoreExists = DB::table('transmuted_grade')
                    ->where('score_bracket', $totalScore)
                    ->exists();

                if (!$scoreExists) {
                    $warnings[] = "⚠️WARNING! The total score of $totalScore for " . ucfirst($category) . " in $term does not exist in the database (the system cannot calculate, please change the total score).";
                }
            }

            // Save or update for each term
            Percentage::updateOrCreate(
                ['classID' => $class->id, 'periodic_term' => $term],
                [
                    'quiz_percentage' => $request->input("quiz_percentage.$term") ?? 0,
                    'quiz_total_score' => $request->input("quiz_total_score.$term") ?? 0,
                    'attendance_percentage' => $request->input("attendance_percentage.$term") ?? 0,
                    'attendance_total_score' => $request->input("attendance_total_score.$term") ?? 0,
                    'assignment_percentage' => $request->input("assignment_percentage.$term") ?? 0,
                    'assignment_total_score' => $request->input("assignment_total_score.$term") ?? 0,
                    'exam_percentage' => $request->input("exam_percentage.$term") ?? 0,
                    'exam_total_score' => $request->input("exam_total_score.$term") ?? 0,
                ]
            );
        }

        // Redirect with warnings if any
        return redirect()->route("class.show", $class)
            ->with('success', 'Data saved successfully.')
            ->with('warnings', $warnings);
    }



    public function addQuizAndScore(Request $request, $class)
    {
        $scores = $request->input('scores');
        $periodicTerm = $request->input('periodic_term');

        // Retrieve total scores from the percentage table for the specific class
        $percentage = Percentage::where('classID', $class)
            ->where('periodic_term', $periodicTerm)
            ->first();

        if (!$percentage) {
            return redirect()->back()->with('error', 'Percentage data not found for this class.');
        }

        foreach ($scores as $studentId => $fields) {
            $classStudent = Classes_Student::where('classID', $class)
                ->where('studentID', $studentId)
                ->first();

            $studentName = $classStudent->name ?? "Student ID $studentId"; // Fetch the student record

            // Validate scores against total scores from percentage table
            if (($fields['quizzez'] ?? 0) > $percentage->quiz_total_score) {
                return redirect()->back()->with('error', "Quiz score for {$studentName} in {$periodicTerm} exceeds the total score.");
            }
            if (($fields['attendance_behavior'] ?? 0) > $percentage->attendance_total_score) {
                return redirect()->back()->with('error', "Attendance score for {$studentName} in {$periodicTerm} exceeds the total score.");
            }
            if (($fields['assignments'] ?? 0) > $percentage->assignment_total_score) {
                return redirect()->back()->with('error', "Assignment score for {$studentName} in {$periodicTerm} exceeds the total score.");
            }
            if (($fields['exam'] ?? 0) > $percentage->exam_total_score) {
                return redirect()->back()->with('error', "Exam score for {$studentName} in {$periodicTerm} exceeds the total score.");
            }

            // Check for existing record
            $existingRecord = QuizzesAndScores::where('classID', $class)
                ->where('studentID', $studentId)
                ->where('periodic_term', $periodicTerm)
                ->first();

            if ($existingRecord) {
                $existingRecord->update([
                    'quizzez' => $fields['quizzez'] ?? $existingRecord->quizzez,
                    'attendance_behavior' => $fields['attendance_behavior'] ?? $existingRecord->attendance_behavior,
                    'assignments' => $fields['assignments'] ?? $existingRecord->assignments,
                    'exam' => $fields['exam'] ?? $existingRecord->exam,
                    'updated_at' => now(),
                ]);
            } else {
                QuizzesAndScores::create([
                    'classID' => $class,
                    'studentID' => $studentId,
                    'periodic_term' => $periodicTerm,
                    'quizzez' => $fields['quizzez'] ?? null,
                    'attendance_behavior' => $fields['attendance_behavior'] ?? null,
                    'assignments' => $fields['assignments'] ?? null,
                    'exam' => $fields['exam'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Scores updated successfully.');
    }

    public function lockInGrades(Request $request)
    {
        foreach ($request->grades as $grade) {
            $classInfo = Classes::find($grade['classID']); // Get class info

            // Fetch student info correctly
            $studentInfo = Classes_Student::where('studentID', $grade['studentID'])->first();

            // Update or insert the active grade record
            DB::table('final_grade')->updateOrInsert(
                [
                    'classID' => $grade['classID'],
                    'studentID' => $grade['studentID']
                ],
                [
                    'subject_code' => optional($classInfo)->subject_code,
                    'descriptive_title' => optional($classInfo)->descriptive_title,
                    'instructor' => optional($classInfo)->instructor,
                    'academic_period' => optional($classInfo)->academic_period,
                    'schedule' => optional($classInfo)->schedule,
                    'name' => optional($studentInfo)->name,
                    'email' => optional($studentInfo)->email,
                    'department' => optional($studentInfo)->department,
                    'prelim' => $grade['prelim'],
                    'midterm' => $grade['midterm'],
                    'semi_finals' => $grade['semi_finals'],
                    'final' => $grade['final'],
                    'remarks' => $grade['remarks'],
                    'status' => 'Locked', // Add status field here
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        return back()->with('success', 'Final grades have been locked in successfully!');
    }

    public function UnlockGrades()
    {
        DB::table('final_grade')->update(['status' => null]);

        return back()->with('success', 'Final grades have been unlocked successfully!');
    }

}
