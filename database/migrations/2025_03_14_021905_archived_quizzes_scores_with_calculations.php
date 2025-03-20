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
        if (!Schema::hasTable('archived_quizzesandscores')) {
            Schema::create('archived_quizzesandscores', function (Blueprint $table) {
                $table->id();
                $table->integer('classID');
                $table->string('subject_code');
                $table->string('descriptive_title');
                $table->string('instructor');
                $table->integer('studentID');
                $table->string('periodic_term');
                $table->integer('quiz_percentage')->nullable();
                $table->integer('quiz_total_score')->nullable();
                $table->decimal('quizzez', 5, 2)->nullable();

                $table->integer('attendance_percentage')->nullable();
                $table->integer('attendance_total_score')->nullable();
                $table->decimal('attendance_behavior', 5, 2)->nullable();

                $table->integer('assignment_percentage')->nullable();
                $table->integer('assignment_total_score')->nullable();
                $table->decimal('assignments', 5, 2)->nullable();

                $table->integer('exam_percentage')->nullable();
                $table->integer('exam_total_score')->nullable();
                $table->decimal('exam', 5, 2)->nullable();
                $table->string('academic_period');
                $table->string('academic_year')->nullable();
                $table->timestamps();






            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
