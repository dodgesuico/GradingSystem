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
        if (!Schema::hasTable('transmuted_grade')) {
            Schema::create('transmuted_grade', function (Blueprint $table) {
                $table->id();

                $table->decimal('score_bracket', 5, 2);

                $table->decimal('score', 5, 2);

                $table->decimal('transmuted_grade', 5, 2);

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
