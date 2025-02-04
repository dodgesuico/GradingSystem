<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::view("/", "welcome");

Route::get("/login", [AuthController::class, "login"])->name('login');
Route::get("/register", [AuthController::class, 'register'])->name('register');


Route::post("/login", [AuthController::class, 'LoginPost'])->name('login.post');
Route::post("/register", [AuthController::class, 'RegisterPost'])->name('register.post');