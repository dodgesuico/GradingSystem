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
        if (!Schema::hasTable('grade_logs')) {
            Schema::create('grade_logs', function (Blueprint $table) {
                $table->id();
                $table->integer('classID');
                $table->string('subject_code');
                $table->string('descriptive_title');
                $table->string('instructor');
                $table->string('academic_period');
                $table->string('schedule');
                $table->integer('studentID');
                $table->string('name');
                $table->string('email');
                $table->string('department');
                $table->decimal('prelim', 5,2);
                $table->decimal('midterm', 5,2);
                $table->decimal('semi_finals', 5,2);
                $table->decimal('final', 5,2);
                $table->string('remarks');
                $table->string('status');
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
