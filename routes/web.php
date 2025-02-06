<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeanController;
use App\Http\Controllers\RegistrarController;
use App\Http\Controllers\ForgotPasswordController;



Route::middleware("auth")->group(function (){
    Route::view("/", "index")->name("index");
});

Route::view("/", "index")->name("index");

Route::get("/admin_dashboard", [AdminController::class, "index"])->name('admin');
Route::get("/registrar_dashboard", [RegistrarController::class, "index"])->name('registrar');
Route::get("/dean_dashboard", [DeanController::class, "index"])->name('dean');
Route::get("/teacher_dashboard", [TeacherController::class, "index"])->name('teacher');
Route::get("/login", [AuthController::class, "login"])->name('login');
Route::get("/register", [AuthController::class, 'register'])->name('register');


Route::post("/login", [AuthController::class, 'LoginPost'])->name('login.post');
Route::post("/register", [AuthController::class, 'RegisterPost'])->name('register.post');
Route::post('/logout', function () {Auth::logout(); return redirect('/login')->with('success', 'You have been logged out successfully.'); })->name('logout');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');