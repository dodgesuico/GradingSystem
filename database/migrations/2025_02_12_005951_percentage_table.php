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
        if (!Schema::hasTable('percentage')) {
            Schema::create('percentage', function (Blueprint $table) {
                $table->id();
                $table->integer('classID');
                $table->string('periodic_term');
                $table->decimal('quiz_percentage', 5, 2); // Allows values like 99.99
                $table->decimal('quiz_total_score', 10, 2);
                $table->decimal('attendance_percentage', 5, 2);
                $table->decimal('attendance_total_score', 10, 2);
                $table->decimal('assignment_percentage', 5, 2);
                $table->decimal('assignment_total_score', 10, 2);
                $table->decimal('exam_percentage', 5, 2);
                $table->decimal('exam_total_score', 10, 2);
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
