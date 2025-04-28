<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\SalaryController;
use App\Http\Controllers\Admin\PerformanceController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.employees.index');
        } else {
            return redirect()->route('employee.attendance.index');
        }
    }
    return redirect()->route('login');
})->name('home');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/verify-email/{token}', [RegisterController::class, 'verify'])->name('verify.email');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::resource('employees', EmployeeController::class)->except(['show']);
        Route::resource('departments', DepartmentController::class)->except(['show']);
        Route::resource('positions', PositionController::class)->except(['show']);
        Route::resource('attendance', AttendanceController::class)->except(['show']);
        Route::resource('salary', SalaryController::class)->except(['show']);
        Route::resource('performance', PerformanceController::class)->except(['show']);
    });

    // Employee Routes
    Route::middleware(['role:employee'])->prefix('employee')->group(function () {
        Route::get('/dashboard', function () {
            return view('employee.dashboard');
        })->name('employee.dashboard');

        // Attendance routes
        Route::get('/attendance', [App\Http\Controllers\Employee\AttendanceController::class, 'index'])->name('attendanceIndex');
        Route::post('/attendance/check-in', [App\Http\Controllers\Employee\AttendanceController::class, 'checkIn'])->name('employee.attendance.checkIn');
        Route::post('/attendance/check-out', [App\Http\Controllers\Employee\AttendanceController::class, 'checkOut'])->name('employee.attendance.checkOut');

        // Salary routes
        Route::get('/salaries', [App\Http\Controllers\Employee\EmployeeSalaryController::class, 'index'])->name('salaryIndex');

        // Performance routes
        Route::get('/performance', [App\Http\Controllers\Employee\PerformanceController::class, 'index'])->name('performanceIndex');
    });
});
