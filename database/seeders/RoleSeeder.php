<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat roles
        $roles = ['admin', 'asesor', 'user'];
        foreach ($roles as $r) {
            Role::findOrCreate($r);
        }

        // Contoh admin default
        if (! User::where('email', 'admin@example.com')->exists()) {
            $admin = User::create([
                'name' => 'Admin',
                'nia' => 'ADM-0001',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]);

            $admin->detail()->create([
                'gender' => 'L',
                'home_city' => 'Jakarta',
            ]);

            $admin->assignRole('admin');
        }
    }
}
