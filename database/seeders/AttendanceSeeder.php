<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        Attendance::create([
            'title' => 'Sample Title',
            'description' => 'Sample Description',
            'type' => 'asesor', // must be 'asesor', 'internal', or 'umum'
            'created_by' => $user ? $user->id : 1,
            'slug' => 'sample-title',
            'start_date' => now(),
            'end_date' => now()->addDay(),
        ]);

    }
}
