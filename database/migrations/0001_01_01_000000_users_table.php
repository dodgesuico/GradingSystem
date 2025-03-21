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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->integer("studentID")->unique();
                $table->string('name');
                $table->string('gender');
                $table->string('email')->unique();
                $table->string('department')->nullable();
                $table->string('password');
                $table->string("role");
                $table->timestamps();
            });


            DB::table('users')->insert([
                ['studentID' => '219286', 'name' => 'Dodge Nicholson P. Suico', 'gender' => 'male', 'email' => 'dodgesuico@ckcm.edu.ph', 'department' => 'College of Computer Science', 'password' => bcrypt('12345'), 'role' => 'student', 'created_at' => now(), 'updated_at' => now()],
                ['studentID' => '214561', 'name' => 'Reynaldo G. Lauron', 'gender' => 'male', 'email' => 'lauron@ckcm.edu.ph', 'department' => 'College of Computer Science', 'password' => bcrypt('12345'), 'role' => 'student', 'created_at' => now(), 'updated_at' => now()],
                ['studentID' => '256672', 'name' => 'Khemark Ocariza', 'gender' => 'male', 'email' => 'khemark@ckcm.edu.ph', 'department' => 'College of Computer Science', 'password' => bcrypt('12345'), 'role' => 'student', 'created_at' => now(), 'updated_at' => now()],
                ['studentID' => '222222', 'name' => 'Neil Vincent Canama', 'gender' => 'male', 'email' => 'Vincent@ckcm.edu.ph', 'department' => 'College of Computer Science', 'password' => bcrypt('12345'), 'role' => 'instructor', 'created_at' => now(), 'updated_at' => now()],
                ['studentID' => '242424', 'name' => 'Marjon D. Ligan', 'gender' => 'male', 'email' => 'Ligan@ckcm.edu.ph', 'department' => 'College of Business Administration', 'password' => bcrypt('12345'), 'role' => 'instructor', 'created_at' => now(), 'updated_at' => now()],
                ['studentID' => '265624', 'name' => 'Marjun Senarlo', 'gender' => 'male', 'email' => 'Senarlo@ckcm.edu.ph', 'department' => 'College of Computer Science', 'password' => bcrypt('12345'), 'role' => 'dean,instructor', 'created_at' => now(), 'updated_at' => now()],
                ['studentID' => '256456', 'name' => 'Bevelyn Ganuhay', 'gender' => 'female', 'email' => 'Ganuhay@ckcm.edu.ph', 'department' => 'N/A', 'password' => bcrypt('12345'), 'role' => 'registrar', 'created_at' => now(), 'updated_at' => now()],
                ['studentID' => '000000', 'name' => 'Admin', 'gender' => 'admin', 'email' => 'admin@ckcm.edu.ph', 'department' => 'Department of System32', 'password' => bcrypt('12345'), 'role' => 'admin', 'created_at' => now(), 'updated_at' => now()],

            ]);
        }


        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
