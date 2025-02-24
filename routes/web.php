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
use App\Http\Controllers\UserController;
use App\Http\Controllers\AllGradesController;

/*
|--------------------------------------------------------------------------
| Guest Routes (For Users Not Logged In)
|--------------------------------------------------------------------------
|
| These routes are only accessible to guests. If a user is logged in,
| they will be redirected to their dashboard instead.
|
*/
Route::middleware(['guest'])->group(function () {
    Route::get("/login", [AuthController::class, "login"])->name('login');
    Route::get("/register", [AuthController::class, 'register'])->name('register');
    Route::post("/login", [AuthController::class, 'LoginPost'])->name('login.post');
    Route::post("/register", [AuthController::class, 'RegisterPost'])->name('register.post');

    // Forgot & Reset Password Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (For Logged-in Users)
|--------------------------------------------------------------------------
|
| These routes are only accessible when logged in. If the user is not
| logged in, they will be redirected to the login page automatically.
|
*/

Route::middleware(['auth'])->group(function () {
    Route::get("/", [IndexController::class, "index"])->name("index");

    // Admin
    Route::get("/index", [AdminController::class, "index"])->name('admin');

    // Registrar
    Route::get("/registrar_dashboard", [RegistrarController::class, "index"])->name('registrar');
    Route::get("/registrar_classes", [RegistrarController::class, "registrar_classes"])->name('registrar_classes');
    Route::post("/registrar_classes", [RegistrarController::class, "CreateClass"])->name('classes.create');
    Route::put("/registrar_dashboard/{class}", [RegistrarController::class, "EditClass"])->name('classes.update');
    Route::delete("/registrar_dashboard/{class}", [RegistrarController::class, "DeleteClass"])->name('classes.destroy');
    Route::get('/classes/class={class}', [RegistrarController::class, 'show'])->name('class.show');
    Route::post('/classes/class={class}', [RegistrarController::class, 'addstudent'])->name('class.addstudent');
    Route::delete('/classes/class={class}/student={student}', [RegistrarController::class, 'removestudent'])->name('class.removestudent');
    Route::put('/classes/class={class}', [RegistrarController::class, 'addPercentageAndScores'])->name('class.addPercentageAndScores');
    Route::get('/quizzesadded/class={class}', [RegistrarController::class, 'show'])->name('class.quizzes');
    Route::put('/quizzesadded/class={class}', [RegistrarController::class, 'addQuizAndScore'])->name('class.addquizandscore');

    Route::post('/lockedfinalgrade', [RegistrarController::class, 'LockInGrades'])->name('finalgrade.lock');
    Route::post('/savefinalgrade', [RegistrarController::class, 'SubmitGrades'])->name('finalgrade.save');
    Route::post('/unlockfinalgrade', [RegistrarController::class, 'UnlockGrades'])->name('finalgrade.unlock');


    Route::get('/allgrades', [AllGradesController::class, 'index'])->name('show.grades');

    Route::get('/users', [UserController::class, 'show'])->name('user.show');
    Route::post('/users', [UserController::class, 'editUser'])->name('user.edituser');


    // Dean
    Route::get("/dean_dashboard", [DeanController::class, "index"])->name('dean');

    // Instructor
    Route::get("/instructor_dashboard", [InstructorController::class, "index"])->name('instructor');
    Route::get("/instructor_classes", [InstructorController::class, "classes"])->name('classes');

    // Logout Route
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login')->with('success', 'You have been logged out successfully.');
    })->name('logout');
});
