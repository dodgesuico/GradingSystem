<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrarController;
use App\Http\Controllers\TeacherController;

Route::view("/", "index")->name('index');


Route::get("/teacher_dashboard", [TeacherController::class, "teacher"])->name('teacher_dashboard');


Route::middleware("auth")->group(function (){
    Route::view("/", "welcome")->name("welcome");
});

Route::view("/", "welcome")->name("welcome");

Route::get("/login", [AuthController::class, "login"])->name('login');
Route::get("/register", [AuthController::class, 'register'])->name('register');

Route::post("/login", [AuthController::class, 'LoginPost'])->name('login.post');
Route::post("/register", [AuthController::class, 'RegisterPost'])->name('register.post');
Route::post('/logout', function () {Auth::logout(); return redirect('/login')->with('success', 'You have been logged out successfully.'); })->name('logout');