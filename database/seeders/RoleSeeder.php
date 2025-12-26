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
        $roles = ['admin','adminlanding', 'asesor', 'user'];
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
        if (! User::where('email', 'adminlanding@example.com')->exists()) {
            $adminlanding = User::create([
                'id' => 3, // ensure this id is unused or remove this line to let DB assign it
                'name' => 'Admin Landing',
                'nia' => 'ADL-0001',
                'email' => 'adminlanding@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]);

            $adminlanding->detail()->create([
                'gender' => 'L',
                'home_city' => 'Surabaya',
            ]);

            $adminlanding->assignRole('adminlanding');
        }
        // Contoh asesor default
        if (! User::where('email', 'asesor@example.com')->exists()) {
            $asesor = User::create([
                'name' => 'Asesor',
                'nia' => 'ASE-0001',
                'email' => 'asesor@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]);

            $asesor->detail()->create([
                'gender' => 'L',
                'home_city' => 'Bandung',
            ]);

            $asesor->assignRole('asesor');
        }
    }
}
