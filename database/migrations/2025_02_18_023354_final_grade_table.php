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
        if (!Schema::hasTable('final_grade')) {
            Schema::create('final_grade', function (Blueprint $table) {
                $table->id();
                $table->integer('classID');
                $table->integer('studentID');
                $table->decimal('prelim', 5,2);
                $table->decimal('midterm', 5,2);
                $table->decimal('semi_finals', 5,2);
                $table->decimal('final', 5,2);
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
