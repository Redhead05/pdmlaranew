<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            RolePermissionSeeder::class,
            FillUserSlugsSeeder::class,
            AttendanceSeeder::class,
            AttendanceDetailsSeeder::class,
            CategorySeeder::class,
            GallerySeeder::class,
            NewsSeeder::class,
            EmployeesTableSeeder::class,
            KabkotkecdesCsvSeeder::class,
        ]);
    }
}
