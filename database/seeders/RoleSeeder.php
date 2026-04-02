<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat roles (hanya yang diperlukan)
        $roles = ['admin', 'adminlanding'];
        foreach ($roles as $r) {
            Role::findOrCreate($r);
        }

        // Admin default
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'nia' => 'ADM-0001',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
        if (! $admin->detail) {
            $admin->detail()->create([
                'gender' => 'L',
                'home_city' => 'Jakarta',
            ]);
        }

        // Admin Landing default
        $adminlanding = User::firstOrCreate(
            ['email' => 'adminlanding@example.com'],
            [
                'name' => 'Admin Landing',
                'nia' => 'ADL-0001',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        if (! $adminlanding->hasRole('adminlanding')) {
            $adminlanding->assignRole('adminlanding');
        }
        if (! $adminlanding->detail) {
            $adminlanding->detail()->create([
                'gender' => 'L',
                'home_city' => 'Surabaya',
            ]);
        }
    }
}
