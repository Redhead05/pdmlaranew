<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Generate unique numeric 7-digit slug for users.
     */
    private function uniqueNumericSlug(): string
    {
        do {
            $slug = (string) random_int(1000000, 9999999);
        } while (User::where('slug', $slug)->exists());

        return $slug;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat roles (pastikan guard_name sesuai, biasanya 'web')
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['guard_name' => 'web']);
        $adminlandingRole = Role::firstOrCreate(['name' => 'adminlanding'], ['guard_name' => 'web']);
        $asesorRole = Role::firstOrCreate(['name' => 'asesor'], ['guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user'], ['guard_name' => 'web']);

        // Buat permissions (set guard_name juga)
        $manageUsers = Permission::firstOrCreate(['name' => 'manage users'], ['guard_name' => 'web']);
        $viewDashboard = Permission::firstOrCreate(['name' => 'view dashboard'], ['guard_name' => 'web']);


        // Assign permission ke role
        $adminRole->givePermissionTo([$manageUsers, $viewDashboard]);
        $adminlandingRole->givePermissionTo([$manageUsers, $viewDashboard]);
        $asesorRole->givePermissionTo([$viewDashboard]);
        $userRole->givePermissionTo([$viewDashboard]);

        // Buat user admin
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'nia' => '123456789',
            'name' => 'Admin',
            'password' => Hash::make('password'),
            'is_active' => true,
            'slug' => $this->uniqueNumericSlug(),
        ]);
        $admin->assignRole('admin');

        // Buat user adminlanding
        $adminLanding = User::firstOrCreate([
            'email' => 'adminlanding@example.com',
        ], [
            'nia' => '123456789012',
            'name' => 'Admin Landing',
            'password' => Hash::make('password'),
            'is_active' => true,
            'slug' => $this->uniqueNumericSlug(),
        ]);
        $adminLanding->assignRole('adminlanding');

        // Buat user asesor
        $asesor = User::firstOrCreate([
            'email' => 'asesor@example.com',
        ], [
            'nia' => '1122334455',
            'name' => 'Asesor',
            'password' => Hash::make('password'),
            'is_active' => true,
            'slug' => $this->uniqueNumericSlug(),
        ]);
        $asesor->assignRole('asesor');

        // Buat user biasa
        $user = User::firstOrCreate([
            'email' => 'user@example.com',
        ], [
            'nia' => '987654321',
            'name' => 'User',
            'password' => Hash::make('password'),
            'is_active' => true,
            'slug' => $this->uniqueNumericSlug(),
        ]);
        $user->assignRole('user');
    }
}
