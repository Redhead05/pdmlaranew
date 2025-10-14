<?php

use App\Http\Controllers\Admin\Attendance\AttendanceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
//admin
use App\Http\Controllers\Admin\DashboardController;
//asesor
use App\Http\Controllers\Asesor\DashboardController as AsesorDashboardController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.admin');
        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.admin');
    });

//    Route::get('asesor/dashboard/', function () {
//        return view('menu.asesor.dashboard');
//    })->middleware('role:asesor')->name('dashboard.asesor');

    Route::prefix('asesor')->middleware('role:asesor')->group(function () {
        Route::get('dashboard', [AsesorDashboardController::class, 'index'])->name('dashboard.asesor');
//        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.asesor');
    });

    Route::get('user/dashboard/', function () {
        return view('dashboard.user');
    })->middleware('role:user')->name('dashboard.user');
});
require __DIR__.'/auth.php';
