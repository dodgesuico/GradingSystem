<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('periodic_term', function (Blueprint $table) {
            $table->id();
            $table->integer('classID');
            $table->integer('studentID');
            $table->string('prelim');
            $table->string('midterm');
            $table->string('semi_finals');
            $table->string('finals');
            $table->timestamps();
        });

        Schema::create('quizzes_scores', function (Blueprint $table) {
            $table->id();
            $table->integer('classID');
            $table->integer('studentID');
            $table->string('quizzez');
            $table->string('attendance_behavior');
            $table->string('assignments_participations_project');
            $table->string('exam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
