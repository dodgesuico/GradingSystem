<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::view("/", "welcome");

Route::get("/login", [AuthController::class, "login"]);