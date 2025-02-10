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
        Schema::create('classes_student', function (Blueprint $table) {
            $table->id();
            $table->integer('classId');
            $table->integer('studentID')->unique();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('department');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes_student');
    }
};
