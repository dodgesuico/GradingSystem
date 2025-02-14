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
        Schema::create('percentage', function (Blueprint $table) {
            $table->id();
            $table->integer('classID');
            $table->integer('periodic_term');
            $table->integer('quiz_percentage');
            $table->integer('quiz_total_score');
            $table->integer('attendance_percentage');
            $table->integer('attendance_total_score');
            $table->integer('assignment_participation_project_percentage');
            $table->integer('assignment_participation_project_total_score');
            $table->integer('exam_percentage');
            $table->integer('exam_total_score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
