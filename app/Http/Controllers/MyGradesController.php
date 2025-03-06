<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\AllGrades;
use Illuminate\Http\Request;

class MyGradesController extends Controller
{
  public function showGrades() {
    $userId = Auth::user()->studentID; // Get logged-in user ID

    // Fetch the latest grade per subject for the logged-in user
    $grades = AllGrades::where('studentID', $userId)
        ->orderBy('created_at', 'desc') // Order by newest grade first
        ->get()
        ->unique(function ($grade) {
            return $grade->subject_code . '-' . $grade->descriptive_title;
        });

    return view('my_grades', compact('grades'));
}


}
