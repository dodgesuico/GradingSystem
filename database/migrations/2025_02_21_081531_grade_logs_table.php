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
                $table->string('subject_code')->nullable();
                $table->string('descriptive_title')->nullable();
                $table->integer('units')->nullable();
                $table->string('instructor')->nullable();
                $table->string('academic_period')->nullable();
                $table->string('academic_year')->nullable();
                $table->string('schedule')->nullable();
                $table->integer('studentID');
                $table->string('name')->nullable();
                $table->string('gender')->nullable();
                $table->string('email')->nullable();
                $table->string('department');
                $table->decimal('prelim', 5,2);
                $table->decimal('midterm', 5,2);
                $table->decimal('semi_finals', 5,2);
                $table->decimal('final', 5,2);
                $table->string('remarks')->nullable();
                $table->string('status')->nullable();
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
