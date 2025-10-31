<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//admin
use App\Http\Controllers\Admin\Attendance\AttendanceController;
use App\Http\Controllers\Admin\DashboardController;
//asesor
use App\Http\Controllers\Asesor\DashboardController as AsesorDashboardController;
use App\Http\Controllers\Asesor\Attendance\AttendanceController as AsesorAttendanceController;

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/pub/internal', function () {
    return view('menu.internal');
});
Route::get('/pub/umum', function () {
    return view('menu.umum');
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
   //Route admin
    Route::prefix('admin')->middleware('role:admin')->as('admin.')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('attendance', AttendanceController::class);
        Route::get('/attendance/{slug}/detail', [AttendanceController::class, 'detail'])->name('attendance.detail');
    });

    //Route asesor
    Route::prefix('asesor')->middleware('role:asesor')->as('asesor.')->group(function () {
        Route::get('dashboard', [AsesorDashboardController::class, 'index'])->name('dashboard');
        Route::resource('attendance',AsesorAttendanceController::class);
    });

//    Route::get('user/dashboard/', function () {
//        return view('dashboard.user');
//    })->middleware('role:user')->name('dashboard.user');
});
require __DIR__.'/auth.php';
