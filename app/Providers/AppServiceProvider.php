<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register custom Blade directive for role checking
        // Used by Spatie Laravel Permission package
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        // Note: Authorization for attendance system:
        // - Admin role: Can manage attendances (CRUD) and view all responses
        // - Asesor role: Can submit 'asesor' type attendance forms (authenticated access)
        // - Public: Can access 'umum' and 'internal' forms without authentication
        // TODO: To enforce asesor role on 'asesor' forms, update AttendanceFormController
        //       to check hasRole('asesor') in show() and submit() methods
    }
}
