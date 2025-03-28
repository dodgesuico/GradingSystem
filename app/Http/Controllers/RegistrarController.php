<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\FinalGrade;
use App\Models\Percentage;
use App\Models\QuizzesAndScores;
use App\Models\Classes_Student;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class RegistrarController extends Controller
{
    public function index()
    {
        return view("registrar.registrar_dashboard");
    }

    public function registrar_classes()
    {
        // Fetch all classes
        $classes = Classes::all();

        // Fetch all instructors with role 'instructor'
        $instructors = User::where('role', 'LIKE', '%instructor%')->get();

        // Fetch all student-class relationships
        $classes_student = Classes_Student::all()->groupBy('classID');

        return view('registrar.registrar_classes', compact('classes', 'instructors', 'classes_student'));
    }


    public function CreateClass(Request $request)
    {
        $request->validate([
            "subject_code" => "required",
            "descriptive_title" => "required",
            "units" => "required",
            "instructor" => "required",
            "academic_period" => "required",
            "academic_year" => "required",
            "schedule" => "required",
            "status" => "required",
            "password" => "required",
            "added_by" => "required"
        ]);

        $class = new Classes();
        $class->subject_code = $request->subject_code;
        $class->descriptive_title = $request->descriptive_title;
        $class->units = $request->units;
        $class->instructor = $request->instructor;
        $class->academic_period = $request->academic_period;
        $class->academic_year = $request->academic_year;
        $class->schedule = $request->schedule;
        $class->status = $request->status;
        $class->password = $request->password;
        $class->added_by = $request->added_by;

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
            "units" => "required",
            "instructor" => "required",
            "academic_period" => "required",
            "academic_year" => "required",
            "schedule" => "required",
            "status" => "required",
        ]);

        $class->subject_code = $request->subject_code;
        $class->descriptive_title = $request->descriptive_title;
        $class->units = $request->units;
        $class->instructor = $request->instructor;
        $class->academic_period = $request->academic_period;
        $class->academic_year = $request->academic_year;
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

        $classes = Classes::where('id', $class->id)->first();

        // 💯 This part is untouched and still works for ADDING STUDENTS
        $enrolledStudentIds = Classes_Student::where('classID', $class->id)->pluck('studentID')->toArray();

        $students = User::where('role', 'student')
            ->whereNotIn('studentID', $enrolledStudentIds)
            ->get();

        // 💯 This part still works for displaying ENROLLED STUDENTS
        $classes_student = Classes_Student::where('classID', $class->id)->get();

        // 💯 This part still works for quizzes
        $quizzesandscores = QuizzesAndScores::where('classID', $class->id)->get();

        // 💯 This part still works for percentages
        $percentage = Percentage::where('classID', $class->id)->get();

        // 💯 This part still works for final grades
        $finalGrades = DB::table('final_grade')
            ->where('classID', $class->id)
            ->get();

        // ✅ NOW THIS IS THE NEW PART - FILTER BY DEPARTMENT FOR DEAN
        $user = Auth::user();
        $userRoles = explode(',', $user->role);

        if (in_array('dean', $userRoles)) {
            // ✅ The user is a dean, now filter by department
            $userDepartment = $user->department;

            $filteredStudents = Classes_Student::where('classID', $class->id)
                ->where('department', $userDepartment)
                ->get();
        } else {
            // ✅ If the user is not a dean, show all students
            $filteredStudents = Classes_Student::where('classID', $class->id)->get();
        }

        // ✅ Now pass EVERYTHING to the Blade (including the new filtered students)
        return view('registrar.registrar_classes_view', compact(
            'class',
            'classes',
            'students',
            'classes_student',
            'quizzesandscores',
            'percentage',
            'finalGrades',
            'filteredStudents'
        ));
    }

    public function importCSV(Request $request, $class)
    {
        // Fetch class model
        $class = Classes::findOrFail($class);

        // Validate file
        $request->validate([
            'students_csv' => 'required|mimes:csv,txt|max:2048'
        ]);

        // Read CSV file
        $file = $request->file('students_csv');
        $csvData = array_map('str_getcsv', file($file));

        // Remove CSV header row
        array_shift($csvData);

        $students = [];
        $insertedStudentIDs = [];

        foreach ($csvData as $row) {
            if (count($row) < 5) {
                continue; // Skip invalid rows
            }

            $fullname = trim($row[2] . " " . $row[3] . " " . $row[1]);

            $students[] = [
                'studentID'  => $row[4],
                'email'      => $row[5],
                'name'       => $fullname,
                'gender'     => null,
                'department' => 'College of Computer Science',
                'classID'    => $class->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Collect student IDs for periodic term insertion
            $insertedStudentIDs[] = $row[4];
        }

        // Bulk insert students
        Classes_Student::insert($students);

        // Array of periodic terms
        $periodicTerms = ['Prelim', 'Midterm', 'Semi-Finals', 'Finals'];

        // Prepare data for quizzes_scores
        $quizScores = [];
        foreach ($insertedStudentIDs as $studentID) {
            foreach ($periodicTerms as $term) {
                $quizScores[] = [
                    'classID'             => $class->id,
                    'studentID'           => $studentID,
                    'periodic_term'       => $term,
                    'quizzez'             => 0,  // Default 0
                    'attendance_behavior' => 0,  // Default 0
                    'assignments'         => 0,  // Default 0
                    'exam'                => 0,  // Default 0
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ];
            }
        }

        // Bulk insert quiz scores
        QuizzesAndScores::insert($quizScores);

        return back()->with('success', 'Students imported successfully.');
    }


    public function addstudent(Request $request, Classes $class)
    {
        $request->validate([
            "student_id" => "required",
            "name" => "required",
            "gender" => "required",
            "email" => "required|email",
            "department" => "required",
        ]);

        // Create a new instance of Classes_Student and assign the values
        $classStudent = new Classes_Student();
        $classStudent->classID = $class->id;
        $classStudent->studentID = $request->student_id;
        $classStudent->name = $request->name;
        $classStudent->gender = $request->gender;
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
                $quizzesandscores->quizzez = 0;              // Default 0
                $quizzesandscores->attendance_behavior = 0;  // Default 0
                $quizzesandscores->assignments = 0;          // Default 0
                $quizzesandscores->exam = 0;                 // Default 0
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

    public function initializeGrades(Request $request)
    {
        // Check if the grades are empty or null
        if (empty($request->grades)) {
            return back()->with('error', 'No students yet, you can\'t initialize.');
        }

        foreach ($request->grades as $grade) {
            $classInfo = Classes::find($grade['classID']); // Get class info

            // Fetch student info correctly
            $studentInfo = Classes_Student::where('studentID', $grade['studentID'])->first();

            // Initialize all grades with "Initialized" status
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
                    'gender' => optional($studentInfo)->gender,
                    'email' => optional($studentInfo)->email,
                    'department' => optional($studentInfo)->department,
                    'prelim' => $grade['prelim'],
                    'midterm' => $grade['midterm'],
                    'semi_finals' => $grade['semi_finals'],
                    'final' => $grade['final'],
                    'remarks' => $grade['remarks'],
                    'status' => '', // ✅ Setting initial status here
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        return back()->with('success', 'Grades have been initialized successfully!');
    }


    public function lockInGrades(Request $request)
    {
        // Check if the grades are empty or null
        if (empty($request->grades)) {
            return back()->with('error', 'No students yet, you can\'t lock.');
        }

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
                    'gender' => optional($studentInfo)->gender,
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

            $classIDs[] = $grade['classID'];
        }

        Classes::whereIn('id', $classIDs)->update(['status' => 'Locked']);

        return back()->with('success', 'Final grades have been locked successfully!');
    }

    public function UnlockGrades(Request $request)
    {
        $department = $request->input('department');
        $classID = $request->input('classID'); // 🔥 Include classID

        if (!$department || !$classID) {
            return back()->with('error', 'Invalid request. No department or class selected.');
        }

        // Unlock grades only for the specified department and class
        DB::table('final_grade')
            ->where('department', $department)
            ->where('classID', $classID) // 🔥 Ensure only this class is affected
            ->update(['status' => null]);

        Classes::where('id', $classID)->update(['status' => 'Active']);

        return back()->with('success', "Final grades for $department (Class ID: $classID) have been unlocked!");
    }


    public function SubmitGrades(Request $request)
    {
        $department = $request->input('department');
        $classID = $request->input('classID'); // 🔥 Include classID

        if (!$department || !$classID) {
            return back()->with('error', 'Invalid request. No department or class selected.');
        }

        // Update submit_status to 'Submitted' for locked grades in the selected department and class
        DB::table('final_grade')
            ->where('department', $department)
            ->where('classID', $classID) // 🔥 Ensure only this class is affected
            ->where('status', 'Locked')
            ->update([
                'submit_status' => 'Submitted',
                'dean_status' => '',
                'comment' => '',
                'updated_at' => now(),
            ]);


        Classes::where('id', $classID)->update(['status' => 'Submitted, Waiting for approval']);

        return back()->with('success', "Grades for $department (Class ID: $classID) have been submitted!");
    }


    public function submitDecision(Request $request)
    {
        // Validate input
        $request->validate([
            'dean_status' => 'required',
            'classID' => 'required',
            'department' => 'required', // 🔥 Ensure department is required
            'comment' => 'nullable|string'
        ]);

        // Build update data
        $updateData = [
            'dean_status' => $request->dean_status,
            'comment' => $request->comment,
            'updated_at' => now()
        ];

        // ✅ If "Returned", also update submit_status & class status
        if ($request->dean_status == 'Returned') {
            $updateData['submit_status'] = 'Returned';

            // 🔥 Update class status to "Rejected"
            Classes::where('id', $request->classID)->update(['status' => 'Rejected']);
        }

        // ✅ If "Confirmed", update submit_status & class status
        if ($request->dean_status == 'Confirmed') {
            $updateData['submit_status'] = 'Submitted';

            // 🔥 Update class status to "Approved"
            Classes::where('id', $request->classID)->update(['status' => 'Approved']);
        }


        // ✅ Update only records matching classID and department
        DB::table('final_grade')
            ->where('classID', $request->classID)
            ->where('department', $request->department)
            ->update($updateData);

        return back()->with('success', 'Dean’s decision has been submitted successfully!');
    }


    public function submitFinalGrades(Request $request)
    {
        if (empty($request->grades)) {
            return back()->with('error', 'No students selected, you can\'t lock.');
        }

        $selectedDepartment = $request->department;
        $classIDs = [];

        foreach ($request->grades as $grade) {
            // 🔹 Get student info (only from the selected department)
            $studentInfo = Classes_Student::where('studentID', $grade['studentID'])
                ->where('department', $selectedDepartment)
                ->first();

            if ($studentInfo) {
                $classInfo = Classes::find($grade['classID']); // Get class details
                $subjectCode = optional($classInfo)->subject_code;
                $descriptiveTitle = optional($classInfo)->descriptive_title;
                $instructor = optional($classInfo)->instructor;
                $academicYear = optional($classInfo)->academic_year; // ✅ Get academic year
                $academicPeriod = optional($classInfo)->academic_period;

                // ✅ Fetch quizzes and scores for this student from `quizzes_scores`
                $quizzesScores = DB::table('quizzes_scores')
                    ->where('classID', $grade['classID'])
                    ->where('studentID', $grade['studentID'])
                    ->get();

                // ✅ Insert into `archived_quizzesandscores`
                foreach ($quizzesScores as $score) {
                    // 🔹 Fetch percentage and total score from `percentage` table
                    $percentageData = DB::table('percentage')
                        ->where('classID', $score->classID)
                        ->first();

                    // ✅ Insert archived record with percentage and total score
                    DB::table('archived_quizzesandscores')->insert([
                        'classID' => $score->classID,
                        'subject_code' => $subjectCode,
                        'descriptive_title' => $descriptiveTitle,
                        'instructor' => $instructor,
                        'studentID' => $score->studentID,
                        'periodic_term' => $score->periodic_term,
                        'quiz_percentage' => $percentageData->quiz_percentage ?? null,
                        'quiz_total_score' => $percentageData->quiz_total_score ?? null,
                        'quizzez' => $score->quizzez,
                        'attendance_percentage' => $percentageData->attendance_percentage ?? null,
                        'attendance_total_score' => $percentageData->attendance_total_score ?? null,
                        'attendance_behavior' => $score->attendance_behavior,
                        'assignment_percentage' => $percentageData->assignment_percentage ?? null,
                        'assignment_total_score' => $percentageData->assignment_total_score ?? null,
                        'assignments' => $score->assignments,
                        'exam_percentage' => $percentageData->exam_percentage ?? null,
                        'exam_total_score' => $percentageData->exam_total_score ?? null,
                        'exam' => $score->exam,
                        'academic_period' => $academicPeriod,
                        'academic_year' => $academicYear, // ✅ Save academic year
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // ✅ Delete the student's quizzes and scores after transferring
                DB::table('quizzes_scores')
                    ->where('classID', $grade['classID'])
                    ->where('studentID', $grade['studentID'])
                    ->delete();

                // ✅ Save student grade logs (final grades)
                DB::table('grade_logs')->insert([
                    'classID' => $grade['classID'],
                    'studentID' => $grade['studentID'],
                    'subject_code' => optional($classInfo)->subject_code,
                    'descriptive_title' => optional($classInfo)->descriptive_title,
                    'units' => optional($classInfo)->units,
                    'instructor' => optional($classInfo)->instructor,
                    'academic_period' => optional($classInfo)->academic_period,
                    'academic_year' => $academicYear,
                    'schedule' => optional($classInfo)->schedule,
                    'name' => $studentInfo->name,
                    'gender' => $studentInfo->gender,
                    'email' => $studentInfo->email,
                    'department' => $selectedDepartment,
                    'prelim' => $grade['prelim'],
                    'midterm' => $grade['midterm'],
                    'semi_finals' => $grade['semi_finals'],
                    'final' => $grade['final'],
                    'remarks' => $grade['remarks'],
                    'status' => 'Approved',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]);

                // ✅ Remove student from `classes_student`
                Classes_Student::where('studentID', $grade['studentID'])
                    ->where('classID', $grade['classID'])
                    ->delete();

                // ✅ Remove student from `final_grades` after locking
                DB::table('final_grade')
                    ->where('classID', $grade['classID'])
                    ->where('studentID', $grade['studentID'])
                    ->delete();

                $classIDs[] = $grade['classID'];
            }
        }

        Classes::whereIn('id', array_unique($classIDs))->update(['status' => 'Active']);

        return back()->with('success', 'Final grades for ' . $selectedDepartment . ' have been locked successfully!');
    }
}
