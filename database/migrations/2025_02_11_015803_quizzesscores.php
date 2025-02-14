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
        Schema::create('quizzes_scores', function (Blueprint $table) {
            $table->id();
            $table->integer('classID');
            $table->integer('studentID');
            $table->string('periodic_term');
            $table->string('quizzez');
            $table->string('attendance_behavior');
            $table->string('assignments');
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
