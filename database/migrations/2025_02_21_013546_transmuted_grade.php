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
                ['score_bracket' => '10.00', 'score' => '10.00', 'transmuted_grade' => '1.00', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '10.00', 'score' => '9.00', 'transmuted_grade' => '1.50', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '10.00', 'score' => '8.00', 'transmuted_grade' => '1.50', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '10.00', 'score' => '7.00', 'transmuted_grade' => '1.50', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '10.00', 'score' => '6.00', 'transmuted_grade' => '1.50', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '10.00', 'score' => '5.00', 'transmuted_grade' => '1.50', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '10.00', 'score' => '3.00', 'transmuted_grade' => '1.50', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '10.00', 'score' => '2.00', 'transmuted_grade' => '1.50', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '10.00', 'score' => '0.00', 'transmuted_grade' => '1.50', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '20.00', 'score' => '20.00', 'transmuted_grade' => '1.00', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '20.00', 'score' => '19.00', 'transmuted_grade' => '1.25', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '20.00', 'score' => '18.00', 'transmuted_grade' => '1.50', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '20.00', 'score' => '17.00', 'transmuted_grade' => '1.75', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '20.00', 'score' => '16.00', 'transmuted_grade' => '2.00', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '20.00', 'score' => '15.00', 'transmuted_grade' => '2.25', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '20.00', 'score' => '14.00', 'transmuted_grade' => '2.50', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '20.00', 'score' => '13.00', 'transmuted_grade' => '2.75', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '20.00', 'score' => '12.00', 'transmuted_grade' => '3.00', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '20.00', 'score' => '11.00', 'transmuted_grade' => '4.00', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '20.00', 'score' => '6.00', 'transmuted_grade' => '4.00', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '20.00', 'score' => '5.00', 'transmuted_grade' => '5.00', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '20.00', 'score' => '0.00', 'transmuted_grade' => '5.00', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '30.00', 'transmuted_grade' => '1.00', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '29.00', 'transmuted_grade' => '1.00', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '28.00', 'transmuted_grade' => '1.25', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '27.00', 'transmuted_grade' => '1.50', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '26.00', 'transmuted_grade' => '1.50', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '25.00', 'transmuted_grade' => '1.75', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '24.00', 'transmuted_grade' => '2.00', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '23.00', 'transmuted_grade' => '2.00', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '22.00', 'transmuted_grade' => '2.25', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '21.00', 'transmuted_grade' => '2.50', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '20.00', 'transmuted_grade' => '2.50', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '19.00', 'transmuted_grade' => '2.75', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '18.00', 'transmuted_grade' => '3.00', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '17.00', 'transmuted_grade' => '3.00', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '9.00', 'transmuted_grade' => '4.00', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '8.00', 'transmuted_grade' => '5.00', 'created_at' => now(), 'updated_at' => now()],
                ['score_bracket' => '30.00', 'score' => '0.00', 'transmuted_grade' => '5.00', 'created_at' => now(), 'updated_at' => now()],
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
