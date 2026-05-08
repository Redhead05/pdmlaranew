<?php



//use App\Http\Controllers\Admin\LandingPage\OrganizationStructureController;
use App\Http\Controllers\Admin\MasterLembaga\MasterLembagaController;
use App\Http\Controllers\Admin\Tahap\TahapController;
use App\Http\Controllers\Admin\Tahap\TahapLembagaController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\frontend\NewsfeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Attendance;
use App\Models\User;

//frontend
use App\Http\Controllers\frontend\GalleryController as FrontendGalleryController;
use App\Http\Controllers\EmployeeController as FrontendEmployeeController;
use App\Http\Controllers\frontend\HomeController as FrontendHomeController;
//use App\Http\Controllers\Admin\LandingPage\EmployeeController as AdminEmployeeController;

//adminpage
use App\Http\Controllers\Admin\LandingPage\DashboardController as AdminLandingDashboardController;
use App\Http\Controllers\Admin\LandingPage\FaqController;
use App\Http\Controllers\Admin\LandingPage\ChatController;
use App\Http\Controllers\Admin\LandingPage\EmployeeController;
use App\Http\Controllers\Admin\LandingPage\GalleryController;
use App\Http\Controllers\Admin\LandingPage\HomeController;
use App\Http\Controllers\Admin\LandingPage\NewsController;

//admin
use App\Http\Controllers\Admin\Attendance\AttendanceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KesanggupanController;

//asesor
use App\Http\Controllers\Asesor\DashboardController as AsesorDashboardController;
use App\Http\Controllers\Asesor\Attendance\AttendanceController as AsesorAttendanceController;
use App\Http\Controllers\Asesor\Kesanggupan\KesanggupanController as AsesorKesanggupanController;
use App\Http\Controllers\PublicAttendanceController;
use App\Http\Controllers\Chat\GuestChatController;
use App\Http\Controllers\Chat\GuestBroadcastAuthController;

Route::get('/', [FrontendHomeController::class, 'index'])->name('frontend.pages.home');
Route::get('/news', [NewsfeController::class, 'index'])->name('frontend.pages.news');
Route::get('/news/{slug}', [NewsfeController::class, 'show'])->name('frontend.pages.news-details');
Route::get('/gallery', [FrontendGalleryController::class, 'index'])->name('frontend.pages.gallery');
Route::get('/employees', [FrontendEmployeeController::class, 'index'])->name('frontend.pages.employes');

// public pages: require slug to load specific attendance
Route::get('/pub/internal/{slug}', [PublicAttendanceController::class, 'showInternal'])->name('pub.internal');
Route::get('/pub/umum/{slug}', [PublicAttendanceController::class, 'showUmum'])->name('pub.umum');

Route::post('/attendance/public/store', [PublicAttendanceController::class, 'store'])->name('attendance.public.store');

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dasfhboard');

//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

Route::middleware(['auth', 'verified'])->group(function () {
    // General profile routes (fallback for non-prefixed users)
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Route admin
    Route::prefix('admin')->middleware('role:admin')->as('admin.')->group(function () {
        // Custom routes harus sebelum resource routes
        Route::post('user/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('user.toggle-status');
        Route::post('user/{user}/toggle-location', [UserController::class, 'toggleLocation'])->name('user.toggle-location');

        Route::resource('user', UserController::class);
        // Employee management (admin)
        Route::resource('employees', EmployeeController::class);
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('attendance', AttendanceController::class);
        Route::get('/attendance/{slug}/detail', [AttendanceController::class, 'detail'])->name('attendance.detail');
        Route::resource('tahap', TahapController::class);
        // Tahap -> Lembaga management (upload CSV, list attached lembaga, detach)
        Route::get('tahap/{tahap}/lembaga', [\App\Http\Controllers\Admin\Tahap\TahapLembagaController::class, 'index'])->name('tahap.lembaga.index');
        Route::get('tahap/{tahap}/lembaga/template', [\App\Http\Controllers\Admin\Tahap\TahapLembagaController::class, 'template'])->name('tahap.lembaga.template');
        Route::post('tahap/{tahap}/lembaga/upload', [\App\Http\Controllers\Admin\Tahap\TahapLembagaController::class, 'upload'])->name('tahap.lembaga.upload');
        Route::post('tahap/{tahap}/lembaga/{lembaga}/detach', [\App\Http\Controllers\Admin\Tahap\TahapLembagaController::class, 'detach'])->name('tahap.lembaga.detach');
        // upload/attach handled directly without preview
        // Custom kesanggupan routes (must be before resource to avoid conflicts)
        Route::post('kesanggupan/{tahap}/generate-teams', [KesanggupanController::class, 'generateTeams'])->name('kesanggupan.generate-teams');
        Route::get('kesanggupan/{tahap}/team-draft', [KesanggupanController::class, 'teamDraft'])->name('kesanggupan.team-draft');
        Route::post('kesanggupan/{tahap}/team-draft/assign', [KesanggupanController::class, 'assignDraftMember'])->name('kesanggupan.team-draft.assign');
        Route::post('kesanggupan/{tahap}/team-draft/unassign', [KesanggupanController::class, 'unassignDraftMember'])->name('kesanggupan.team-draft.unassign');
        Route::post('kesanggupan/{tahap}/finalize-teams', [KesanggupanController::class, 'finalizeTeams'])->name('kesanggupan.finalize-teams');
        // Download / Upload / Cancel draft
        Route::get('kesanggupan/{tahap}/team-draft/download', [KesanggupanController::class, 'downloadDraft'])->name('kesanggupan.team-draft.download');
        Route::post('kesanggupan/{tahap}/team-draft/upload', [KesanggupanController::class, 'uploadDraft'])->name('kesanggupan.team-draft.upload');
        Route::post('kesanggupan/{tahap}/team-draft/cancel', [KesanggupanController::class, 'cancelDraft'])->name('kesanggupan.team-draft.cancel');
        Route::post('kesanggupan/{tahap}/team-draft/reopen', [KesanggupanController::class, 'reopenDraft'])->name('kesanggupan.team-draft.reopen');
        Route::resource('kesanggupan', KesanggupanController::class);
        // Master lembaga resource (index will handle AJAX/data responses)
        Route::resource('masterlembaga', MasterLembagaController::class);
    });
    //Route adminlanding
    Route::prefix('adminlanding')->middleware('role:adminlanding|admin')->as('adminlanding.')->group(function () {
        // Profile routes for adminlanding/admin users
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('dashboard', [AdminLandingDashboardController::class, 'index'])->name('dashboard');
        Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
        Route::get('chat/conversations', [ChatController::class, 'conversations'])->name('chat.conversations');
        Route::get('chat/conversations/{conversation}/messages', [ChatController::class, 'messages'])->name('chat.messages');
        Route::post('chat/conversations/{conversation}/reply', [ChatController::class, 'reply'])->name('chat.reply');
        Route::post('chat/conversations/{conversation}/read', [ChatController::class, 'markRead'])->name('chat.read');
        Route::resource('home', HomeController::class);
        Route::resource('gallery', GalleryController::class);
        Route::resource('news', NewsController::class);
        Route::resource('employee', EmployeeController::class);
        Route::resource('faq', FaqController::class);

    });

    //Route asesor
    Route::prefix('asesor')->middleware('role:asesor')->as('asesor.')->group(function () {
        // Profile routes for asesor users
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('dashboard', [AsesorDashboardController::class, 'index'])->name('dashboard');
        Route::resource('attendance',AsesorAttendanceController::class);
        Route::resource('kesanggupan', AsesorKesanggupanController::class);
    });

//    Route::get('user/dashboard/', function () {
//        return view('dashboard.user');
//    })->middleware('role:user')->name('dashboard.user');
});
require __DIR__.'/auth.php';

Route::post('/chat/guest/start', [GuestChatController::class, 'start'])->middleware('throttle:6,1')->name('chat.guest.start');
Route::get('/chat/conversations/{conversation}/messages', [GuestChatController::class, 'messages'])->name('chat.guest.messages');
Route::post('/chat/conversations/{conversation}/messages', [GuestChatController::class, 'send'])->middleware('throttle:30,1')->name('chat.guest.send');
Route::post('/chat/broadcasting/auth', GuestBroadcastAuthController::class)->name('chat.broadcast.auth');
