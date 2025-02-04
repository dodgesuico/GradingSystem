<?php

use Illuminate\Support\Facades\Route;

Route::view("/", "welcome");

Route::get("/login", [AuthController::class, "login"]);