<?php

use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Attendance;
use App\Models\User;

//admin
use App\Http\Controllers\Admin\Attendance\AttendanceController;
use App\Http\Controllers\Admin\DashboardController;
//asesor
use App\Http\Controllers\Asesor\DashboardController as AsesorDashboardController;
use App\Http\Controllers\Asesor\Attendance\AttendanceController as AsesorAttendanceController;
use App\Http\Controllers\PublicAttendanceController;

Route::get('/', function () {
    return view('auth.login');
});
Route::view('/landing', 'frontend.pages.home')->name('frontend.pages.home');
Route::view('/news', 'frontend.pages.news')->name('frontend.pages.news');
Route::view('/news-details', 'frontend.pages.news-details')->name('frontend.pages.news-details');
Route::view('/gallery', 'frontend.pages.gallery')->name('frontend.pages.gallery');
Route::view('/employees', 'frontend.pages.employes')->name('frontend.pages.employes');

// public pages: require slug to load specific attendance
Route::get('/pub/internal/{slug}', [PublicAttendanceController::class, 'showInternal'])->name('pub.internal');
Route::get('/pub/umum/{slug}', [PublicAttendanceController::class, 'showUmum'])->name('pub.umum');

Route::post('/attendance/public/store', [PublicAttendanceController::class, 'store'])->name('attendance.public.store');

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
        Route::resource('user', UserController::class);
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
