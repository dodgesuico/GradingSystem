<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassArchive extends Model
{   protected $table = 'archived_quizzesandscores'; // Ensure this matches your actual table name

    protected $fillable = [
        'classID', 'studentID', 'periodic_term',
        'quiz_percentage', 'quiz_total_score', 'quizzez',
        'attendance_percentage', 'attendance_total_score', 'attendance_behavior',
        'assignment_percentage', 'assignment_total_score', 'assignments',
        'exam_percentage', 'exam_total_score', 'exam',
        'academic_year'
    ];
}
