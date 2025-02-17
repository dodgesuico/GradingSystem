<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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


            DB::table('transmuted_grade')->insert([
                ['score_bracket' => '100.00', 'score' => '90.00', 'transmuted_grade' => '1.00', 'created_at' => now(), 'updated_at' => now()],
            ]);
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
