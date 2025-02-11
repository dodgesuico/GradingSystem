<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeanController;
use App\Http\Controllers\RegistrarController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ForgotPasswordController;



Route::middleware("auth")->group(function () {
    Route::view("/", "index")->name("index");
});

// for users
Route::view("/index",  [IndexController::class, "index"])->name("index");


// for admin
Route::get("/index", [AdminController::class, "index"])->name('admin');

// for registrar
Route::get("/registrar_dashboard", [RegistrarController::class, "index"])->name('registrar');
Route::get("/registrar_classes", [RegistrarController::class, "registrar_classes"])->name('registrar_classes');
Route::post("/registrar_classes", [RegistrarController::class, "CreateClass"])->name('classes.create');
Route::put("/registrar_dashboard/{class}", [RegistrarController::class, "EditClass"])->name('classes.update');
Route::delete("/registrar_dashboard/{class}", [RegistrarController::class, "DeleteClass"])->name('classes.destroy');
Route::get('/registrar_dashboard_view/class={class}', [RegistrarController::class, 'show'])->name('class.show');
Route::post('/registrar_dashboard_view/class={class}', [RegistrarController::class, 'addstudent'])->name('class.addstudent');
Route::delete('/registrar_dashboard_view/class={class}/student={student}', [RegistrarController::class, 'removestudent'])->name('class.removestudent');


// for dean
Route::get("/dean_dashboard", [DeanController::class, "index"])->name('dean');

// for instructor
Route::get("/instructor_dashboard", [InstructorController::class, "index"])->name('instructor');
Route::get("/instructor_classes", [InstructorController::class, "classes"])->name('classes');


// for login
Route::get("/login", [AuthController::class, "login"])->name('login');

// for register
Route::get("/register", [AuthController::class, 'register'])->name('register');



Route::post("/login", [AuthController::class, 'LoginPost'])->name('login.post');
Route::post("/register", [AuthController::class, 'RegisterPost'])->name('register.post');
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login')->with('success', 'You have been logged out successfully.');
})->name('logout');










Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
