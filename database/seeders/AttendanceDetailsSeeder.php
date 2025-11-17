<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceDetailsSeeder extends Seeder

{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $attendances = Attendance::all();

        foreach ($attendances as $attendance) {
            $randomUsers = $users->random(2);

            foreach ($randomUsers as $user) {
                AttendanceDetail::create([
                    'attendance_id' => $attendance->id,
                    'user_id' => $user->id,
                    'signed_at' => Carbon::now(),
                    'signature' => 'dummy_signature_data', // replace with actual signature if needed
                ]);
            }
        }
    }
}
