<?php

// use App\Http\Controllers\Admin\LandingPage\OrganizationStructureController;
use App\Http\Controllers\Admin\Attendance\AttendanceController;
use App\Http\Controllers\Admin\BerkasVisitasiController;
use App\Http\Controllers\Admin\CalendarEventController;
use App\Http\Controllers\Admin\CertificationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KesanggupanController;
use App\Http\Controllers\Admin\LandingPage\ChatController;
use App\Http\Controllers\Admin\LandingPage\DashboardController as AdminLandingDashboardController;
use App\Http\Controllers\Admin\LandingPage\EmployeeController;
use App\Http\Controllers\Admin\LandingPage\FaqController;
use App\Http\Controllers\Admin\LandingPage\GalleryController;
// frontend
use App\Http\Controllers\Admin\LandingPage\HomeController;
use App\Http\Controllers\Admin\LandingPage\NewsController;
use App\Http\Controllers\Admin\MasterLembaga\MasterLembagaController;
// use App\Http\Controllers\Admin\LandingPage\EmployeeController as AdminEmployeeController;

// adminpage
use App\Http\Controllers\Admin\Tahap\GenerationController;
use App\Http\Controllers\Admin\Tahap\TahapController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\Tahap\TahapLembagaController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\User\UserManagementController;
use App\Http\Controllers\Admin\ValidasiController;
use App\Http\Controllers\Asesor\Attendance\AttendanceController as AsesorAttendanceController;
use App\Http\Controllers\Asesor\BerkasVisitasiController as AsesorBerkasVisitasiController;
use App\Http\Controllers\Asesor\CertificationController as AsesorCertificationController;
use App\Http\Controllers\Asesor\DashboardController as AsesorDashboardController;
use App\Http\Controllers\Asesor\Kesanggupan\KesanggupanController as AsesorKesanggupanController;
// admin
use App\Http\Controllers\Asesor\SuratTugasController;
use App\Http\Controllers\Asesor\TicketController as AsesorTicketController;
use App\Http\Controllers\Asesor\ValidasiController as AsesorValidasiController;
use App\Http\Controllers\Asesor\VisitasiController;
use App\Http\Controllers\Chat\GuestBroadcastAuthController;
use App\Http\Controllers\Chat\GuestChatController;
// asesor
use App\Http\Controllers\EmployeeController as FrontendEmployeeController;
use App\Http\Controllers\frontend\GalleryController as FrontendGalleryController;
use App\Http\Controllers\frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\frontend\NewsfeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicAttendanceController;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendHomeController::class, 'index'])->name('frontend.pages.home');
Route::get('/news', [NewsfeController::class, 'index'])->name('frontend.pages.news');
Route::get('/news/{slug}', [NewsfeController::class, 'show'])->name('frontend.pages.news-details');
Route::get('/gallery', [FrontendGalleryController::class, 'index'])->name('frontend.pages.gallery');
Route::get('/employees', [FrontendEmployeeController::class, 'index'])->name('frontend.pages.employes');

// public pages: require slug to load specific attendance
Route::get('/pub/internal/{slug}', [PublicAttendanceController::class, 'showInternal'])->name('pub.internal');
Route::get('/pub/umum/{slug}', [PublicAttendanceController::class, 'showUmum'])->name('pub.umum');

Route::post('/attendance/public/store', [PublicAttendanceController::class, 'store'])->name('attendance.public.store');

// Route::get('/dashboard', function () {
//    return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dasfhboard');

// Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::middleware(['auth', 'verified'])->group(function () {
    // General profile routes (fallback for non-prefixed users)
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifikasi database (semua role terautentikasi)
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Route admin — akses per permission menu (bukan role:admin)
    Route::prefix('admin')->as('admin.')->group(function () {
        // --- view dashboard ---
        Route::middleware('permission:view dashboard')->group(function () {
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('calendar/events', [CalendarEventController::class, 'events'])->name('calendar.events');
            Route::post('calendar/events', [CalendarEventController::class, 'store'])->name('calendar.store');
            Route::delete('calendar/events/{event}', [CalendarEventController::class, 'destroy'])->name('calendar.destroy');
        });

        // --- manage users ---
        Route::middleware('permission:manage users')->group(function () {
            // Custom routes harus sebelum resource routes
            Route::post('user/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('user.toggle-status');
            Route::post('user/{user}/toggle-location', [UserController::class, 'toggleLocation'])->name('user.toggle-location');
            Route::post('user/{user}/toggle-flag', [UserController::class, 'toggleFlag'])->name('user.toggle-flag');
            Route::post('user/toggle-global-flag', [UserController::class, 'toggleGlobalFlag'])->name('user.toggle-global-flag');

            Route::resource('user', UserController::class);
            // Employee management (admin)
            Route::resource('employees', EmployeeController::class);
        });

        // --- akses user management ---
        Route::middleware('permission:akses user management')->group(function () {
            Route::get('user-management', [UserManagementController::class, 'index'])->name('user-management.index');
            Route::post('user-management/toggle-permission', [UserManagementController::class, 'togglePermission'])->name('user-management.toggle-permission');
        });

        // --- akses attendance ---
        Route::middleware('permission:akses attendance')->group(function () {
            Route::resource('attendance', AttendanceController::class);
            Route::get('/attendance/{slug}/detail', [AttendanceController::class, 'detail'])->name('attendance.detail');
        });

        // --- akses certifications ---
        Route::middleware('permission:akses certifications')->group(function () {
            Route::get('certifications/detail/{batch}', [CertificationController::class, 'detail'])->name('certifications.detail');
            Route::resource('certifications', CertificationController::class);
        });

        // --- akses visitasi ---
        Route::middleware('permission:akses visitasi')->group(function () {
            Route::resource('tahap', TahapController::class);
            // Generation: pairing teams <-> lembaga (auto-match + manual override)
            Route::get('tahap/{tahap}/generation', [GenerationController::class, 'index'])->name('tahap.generation.index');
            Route::post('tahap/{tahap}/generate', [GenerationController::class, 'generate'])->name('tahap.generate');
            Route::post('tahap/{tahap}/generation/assign', [GenerationController::class, 'assign'])->name('tahap.generation.assign');
            Route::delete('tahap/{tahap}/generation/{assignment}', [GenerationController::class, 'unassign'])->name('tahap.generation.unassign');
            Route::post('tahap/{tahap}/generation/batch-cancel', [GenerationController::class, 'batchCancel'])->name('tahap.generation.batch-cancel');
            // Pairing tools: DataTables manual override, download/upload, lock/unlock
            Route::get('tahap/{tahap}/generation/unmatched-data', [GenerationController::class, 'unmatchedData'])->name('tahap.generation.data');
            Route::get('tahap/{tahap}/generation/remaining-data', [GenerationController::class, 'remainingLembagas'])->name('tahap.generation.remaining-data');
            Route::get('tahap/{tahap}/generation/pairing-results', [GenerationController::class, 'pairingResults'])->name('tahap.generation.pairing-results');
            Route::get('tahap/{tahap}/generation/tersisa', [GenerationController::class, 'remainingIndex'])->name('tahap.generation.tersisa');
            Route::get('tahap/{tahap}/generation/lembaga-options', [GenerationController::class, 'lembagaOptions'])->name('tahap.generation.lembaga-options');
            Route::get('tahap/{tahap}/generation/download', [GenerationController::class, 'download'])->name('tahap.generation.download');
            Route::post('tahap/{tahap}/generation/upload', [GenerationController::class, 'upload'])->name('tahap.generation.upload');
            Route::post('tahap/{tahap}/generation/upload-confirm', [GenerationController::class, 'uploadConfirm'])->name('tahap.generation.upload-confirm');
            Route::post('tahap/{tahap}/generation/upload-cancel', [GenerationController::class, 'uploadCancel'])->name('tahap.generation.upload-cancel');
            Route::post('tahap/{tahap}/generation/lock', [GenerationController::class, 'lock'])->name('tahap.generation.lock');
            Route::post('tahap/{tahap}/generation/unlock', [GenerationController::class, 'unlock'])->name('tahap.generation.unlock');
            Route::get('tahap/{tahap}/generation/asesor-data', [GenerationController::class, 'asesorData'])->name('tahap.generation.asesor-data');
            Route::post('tahap/{tahap}/generation/asesor-set', [GenerationController::class, 'setAsesor'])->name('tahap.generation.asesor-set');
            Route::get('tahap/{tahap}/generation/results-copy', [GenerationController::class, 'resultsCopy'])->name('tahap.generation.results-copy');
            // Surat tugas: kirim (setelah kunci) + pratinjau admin
            Route::post('tahap/{tahap}/generation/surat-tugas/send', [GenerationController::class, 'sendSuratTugas'])->name('tahap.generation.surat-tugas.send');
            Route::get('tahap/{tahap}/generation/surat-tugas/{run}', [GenerationController::class, 'suratTugas'])->name('tahap.generation.surat-tugas');

            Route::post('tahap/{tahap}/generation/tersisa/{lembaga}/hapus', [GenerationController::class, 'remainingDetach'])->name('tahap.generation.remaining-detach');
            // Tahap -> Lembaga management (upload CSV, list attached lembaga, pilih dari master, detach)
            Route::get('tahap/{tahap}/lembaga', [TahapLembagaController::class, 'index'])->name('tahap.lembaga.index');
            Route::get('tahap/{tahap}/lembaga/pilih', [TahapLembagaController::class, 'pilih'])->name('tahap.lembaga.pilih');
            Route::post('tahap/{tahap}/lembaga/attach', [TahapLembagaController::class, 'attach'])->name('tahap.lembaga.attach');
            Route::get('tahap/{tahap}/lembaga/template', [TahapLembagaController::class, 'template'])->name('tahap.lembaga.template');
            Route::post('tahap/{tahap}/lembaga/upload', [TahapLembagaController::class, 'upload'])->name('tahap.lembaga.upload');
            Route::post('tahap/{tahap}/lembaga/check-npsn', [TahapLembagaController::class, 'checkNpsn'])->name('tahap.lembaga.check-npsn');
            Route::post('tahap/{tahap}/lembaga/{lembaga}/detach', [TahapLembagaController::class, 'detach'])->name('tahap.lembaga.detach');

            // Detail tahap: data kesanggupan asesor + pasangan asesor (generate, manual, Excel)
            Route::get('tahap/{tahap}/kesanggupan/bisa', [TahapController::class, 'bisaData'])->name('tahap.kesanggupan.bisa');
            Route::get('tahap/{tahap}/kesanggupan/tidak-bisa', [TahapController::class, 'tidakBisaData'])->name('tahap.kesanggupan.tidak-bisa');
            Route::get('tahap/{tahap}/kesanggupan/belum-mengisi', [TahapController::class, 'belumMengisiData'])->name('tahap.kesanggupan.belum-mengisi');
            Route::get('tahap/{tahap}/pasangan-asesor/data', [TahapController::class, 'pairsData'])->name('tahap.pairing.data');
            Route::get('tahap/{tahap}/pasangan-asesor/unmatched', [TahapController::class, 'unmatchedData'])->name('tahap.pairing.unmatched');
            Route::get('tahap/{tahap}/pasangan-asesor/asesor-options', [TahapController::class, 'asesorOptions'])->name('tahap.pairing.asesor-options');
            Route::post('tahap/{tahap}/pasangan-asesor/generate', [TahapController::class, 'generatePairs'])->name('tahap.pairing.generate');
            Route::get('tahap/{tahap}/pasangan-asesor/download', [TahapController::class, 'downloadPairs'])->name('tahap.pairing.download');
            Route::post('tahap/{tahap}/pasangan-asesor/upload', [TahapController::class, 'uploadPairs'])->name('tahap.pairing.upload');
            Route::post('tahap/{tahap}/pasangan-asesor/set-slot', [TahapController::class, 'setSlot'])->name('tahap.pairing.set-slot');
            Route::post('tahap/{tahap}/pasangan-asesor/add-member', [TahapController::class, 'addMember'])->name('tahap.pairing.add-member');
            Route::post('tahap/{tahap}/pasangan-asesor/remove-member', [TahapController::class, 'removeMember'])->name('tahap.pairing.remove-member');

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
        });

        // --- akses master lembaga ---
        Route::middleware('permission:akses master lembaga')->group(function () {
            Route::resource('masterlembaga', MasterLembagaController::class);
        });

        // --- akses validasi ---
        Route::middleware('permission:akses validasi')->group(function () {
            // Validasi (form Ya/Tidak + TTD + surat tugas)
            Route::get('validasi', [ValidasiController::class, 'index'])->name('validasi.index');
            Route::post('validasi', [ValidasiController::class, 'store'])->name('validasi.store');
            Route::get('validasi/{validasi}', [ValidasiController::class, 'show'])->name('validasi.show');
            Route::delete('validasi/{validasi}', [ValidasiController::class, 'destroy'])->name('validasi.destroy');
            Route::get('validasi/{validasi}/kesanggupan/bisa', [ValidasiController::class, 'bisaData'])->name('validasi.bisa');
            Route::get('validasi/{validasi}/kesanggupan/tidak-bisa', [ValidasiController::class, 'tidakBisaData'])->name('validasi.tidak-bisa');
            Route::get('validasi/{validasi}/kesanggupan/belum-mengisi', [ValidasiController::class, 'belumMengisiData'])->name('validasi.belum-mengisi');
            Route::post('validasi/{validasi}/set-bisa', [ValidasiController::class, 'setBisa'])->name('validasi.set-bisa');
            Route::post('validasi/{validasi}/set-tidak', [ValidasiController::class, 'setTidak'])->name('validasi.set-tidak');
            Route::post('validasi/{validasi}/set-belum', [ValidasiController::class, 'setBelum'])->name('validasi.set-belum');
            Route::post('validasi/{validasi}/bulk-set-bisa', [ValidasiController::class, 'bulkSetBisa'])->name('validasi.bulk-set-bisa');
            Route::post('validasi/{validasi}/bulk-set-belum', [ValidasiController::class, 'bulkSetBelum'])->name('validasi.bulk-set-belum');
            // Pairing lembaga (visitasi terkunci) ↔ asesor "bisa"
            Route::get('validasi/{validasi}/lembaga', [ValidasiController::class, 'lembagaIndex'])->name('validasi.lembaga.index');
            Route::get('validasi/{validasi}/lembaga/data', [ValidasiController::class, 'lembagaData'])->name('validasi.lembaga.data');
            Route::get('validasi/{validasi}/lembaga/asesor-options', [ValidasiController::class, 'lembagaAsesorOptions'])->name('validasi.lembaga.asesor-options');
            Route::post('validasi/{validasi}/lembaga/assign', [ValidasiController::class, 'lembagaAssign'])->name('validasi.lembaga.assign');
            Route::post('validasi/{validasi}/lembaga/unassign', [ValidasiController::class, 'lembagaUnassign'])->name('validasi.lembaga.unassign');
            Route::get('validasi/{validasi}/lembaga/download', [ValidasiController::class, 'lembagaExport'])->name('validasi.lembaga.download');
            Route::get('validasi/{validasi}/lembaga/copy', [ValidasiController::class, 'lembagaCopy'])->name('validasi.lembaga.copy');
            Route::post('validasi/{validasi}/lembaga/import', [ValidasiController::class, 'lembagaImport'])->name('validasi.lembaga.import');
            Route::post('validasi/{validasi}/lembaga/auto-pair', [ValidasiController::class, 'lembagaAutoPair'])->name('validasi.lembaga.auto-pair');
            Route::post('validasi/{validasi}/lembaga/pair-rule', [ValidasiController::class, 'lembagaPairRule'])->name('validasi.lembaga.pair-rule');
            Route::post('validasi/{validasi}/lock', [ValidasiController::class, 'lock'])->name('validasi.lock');
            Route::post('validasi/{validasi}/unlock', [ValidasiController::class, 'unlock'])->name('validasi.unlock');
            Route::post('validasi/{validasi}/surat-tugas/send', [ValidasiController::class, 'sendSuratTugas'])->name('validasi.surat-tugas.send');
            Route::get('validasi/{validasi}/surat-tugas', [ValidasiController::class, 'suratTugas'])->name('validasi.surat-tugas');
        });

        // --- akses ticket support ---
        Route::middleware('permission:akses ticket support')->group(function () {
            // Ticket support
            Route::get('ticket', [AdminTicketController::class, 'index'])->name('ticket.index');
            Route::get('ticket/{ticket}', [AdminTicketController::class, 'show'])->name('ticket.show');
            Route::post('ticket/{ticket}/open', [AdminTicketController::class, 'open'])->name('ticket.open');
            Route::post('ticket/{ticket}/respond', [AdminTicketController::class, 'respond'])->name('ticket.respond');
        });
    });

    // Berkas visitasi (review asesor) — akses via permission, bukan role admin
    Route::prefix('admin')->middleware('permission:akses berkas visitasi')->as('admin.')->group(function () {
        Route::get('berkas-visitasi', [BerkasVisitasiController::class, 'index'])->name('berkas.index');
        Route::get('berkas-visitasi/data', [BerkasVisitasiController::class, 'data'])->name('berkas.data');
        Route::get('berkas-visitasi/{berkas}', [BerkasVisitasiController::class, 'show'])->name('berkas.show');
        Route::post('berkas-visitasi/{berkas}/approve', [BerkasVisitasiController::class, 'approve'])->name('berkas.approve');
        Route::post('berkas-visitasi/{berkas}/reject', [BerkasVisitasiController::class, 'reject'])->name('berkas.reject');
    });

    // Route adminlanding
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

    // Route asesor
    Route::prefix('asesor')->middleware('role:asesor')->as('asesor.')->group(function () {
        // Profile routes for asesor users
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('dashboard', [AsesorDashboardController::class, 'index'])->name('dashboard');
        Route::get('calendar/events', [AsesorDashboardController::class, 'calendarEvents'])->name('calendar.events');
        Route::resource('attendance', AsesorAttendanceController::class);
        Route::resource('kesanggupan', AsesorKesanggupanController::class);
        Route::get('validasi', [AsesorValidasiController::class, 'index'])->name('validasi.index');
        Route::post('validasi/{validasi}/simpan', [AsesorValidasiController::class, 'simpan'])->name('validasi.simpan');
        Route::get('validasi/penugasan/data', [AsesorValidasiController::class, 'penugasanData'])->name('validasi.penugasan-data');
        Route::get('validasi/{validasi}/surat-tugas/{st}', [AsesorValidasiController::class, 'suratTugas'])->name('validasi.surat-tugas');
        Route::get('visitasi', [VisitasiController::class, 'index'])->name('visitasi.index');
        Route::get('visitasi/data', [VisitasiController::class, 'data'])->name('visitasi.data');
        Route::get('visitasi/{tahap}/berkas', [AsesorBerkasVisitasiController::class, 'index'])->name('visitasi.berkas.index');
        Route::post('visitasi/berkas/check-foto', [AsesorBerkasVisitasiController::class, 'checkFoto'])->name('visitasi.berkas.check-foto');
        Route::post('visitasi/berkas/jarak', [AsesorBerkasVisitasiController::class, 'jarak'])->name('visitasi.berkas.jarak');
        Route::post('visitasi/berkas/store', [AsesorBerkasVisitasiController::class, 'store'])->name('visitasi.berkas.store');
        Route::get('visitasi/berkas/{berkas}', [AsesorBerkasVisitasiController::class, 'show'])->name('visitasi.berkas.show');
        // Ticket support (asesor)
        Route::get('ticket', [AsesorTicketController::class, 'index'])->name('ticket.index');
        Route::post('ticket', [AsesorTicketController::class, 'store'])->name('ticket.store');
        Route::get('ticket/{ticket}', [AsesorTicketController::class, 'show'])->name('ticket.show');
        Route::post('ticket/{ticket}/close', [AsesorTicketController::class, 'close'])->name('ticket.close');
        Route::get('surat-tugas/{tahap}/{st}', [SuratTugasController::class, 'show'])->name('surat-tugas.show');
        // Certifications (asesor)
        Route::get('certifications', [AsesorCertificationController::class, 'index'])->name('certifications.index');
        Route::get('certifications/{certification}/download', [AsesorCertificationController::class, 'download'])->name('certifications.download');
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
