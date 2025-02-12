<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizzesAndScores extends Model
{
    protected $table = 'quizzes_scores';

    protected $fillable = [
        'classID',
        'studentID',
        'periodic_term',
        'quizzez',
        'attendance_behavior',
        'assignment_participation_project_percentage',
        'exam',

    ];
}
