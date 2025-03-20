<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ClassArchive;

class ClassArchiveController extends Controller
{
    public function index()
    {
        // Fetch data and group by classID, periodic_term, and academic_year
        $archivedData = ClassArchive::select(
                'id', 'classID', 'studentID', 'periodic_term',
                'quiz_percentage', 'quiz_total_score', 'quizzez',
                'attendance_percentage', 'attendance_total_score', 'attendance_behavior',
                'assignment_percentage', 'assignment_total_score', 'assignments',
                'exam_percentage', 'exam_total_score', 'exam',
                'academic_year', 'created_at', 'updated_at'
            )
            ->orderBy('academic_year', 'desc')
            ->orderBy('classID')
            ->orderBy('periodic_term')
            ->get()
            ->groupBy(['academic_year', 'classID', 'periodic_term']);

        return view('instructor.my_class_archive', compact('archivedData'));
    }
}
