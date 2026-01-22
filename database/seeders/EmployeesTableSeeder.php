<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeesTableSeeder extends Seeder
{
    public function run()
    {
        $periods = [
            ['start' => 2018, 'end' => 2024],
            ['start' => 2024, 'end' => 2025],
        ];

        $avatars = [
            'assets/blankphotopdm/ExamEmployee.png',
        ];

        foreach ($periods as $period) {
            $start = $period['start'];
            $end = $period['end'];

            // Ketua
            DB::table('employees')->insert([
                'name' => "Ketua {$start}-{$end}",
                'position' => 'Ketua',
                'start_year' => $start,
                'end_year' => $end,
                'email' => "ketua{$start}@example.test",
                'photo' => $avatars[array_rand($avatars)],
                'facebook' => "https://facebook.com/ketua{$start}",
                'instagram' => "https://instagram.com/ketua{$start}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Sekretaris
            DB::table('employees')->insert([
                'name' => "Sekretaris {$start}-{$end}",
                'position' => 'Sekretaris',
                'start_year' => $start,
                'end_year' => $end,
                'email' => "sekretaris{$start}@example.test",
                'photo' => $avatars[array_rand($avatars)],
                'facebook' => "https://facebook.com/sekretaris{$start}",
                'instagram' => "https://instagram.com/sekretaris{$start}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Anggota (5)
            for ($i = 1; $i <= 5; $i++) {
                DB::table('employees')->insert([
                    'name' => "Anggota {$i} {$start}-{$end}",
                    'position' => 'Anggota',
                    'start_year' => $start,
                    'end_year' => $end,
                    'email' => "anggota{$i}{$start}@example.test",
                    'photo' => $avatars[array_rand($avatars)],
                    'facebook' => "https://facebook.com/anggota{$i}{$start}",
                    'instagram' => "https://instagram.com/anggota{$i}{$start}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Sekretariat (17)
            for ($i = 1; $i <= 17; $i++) {
                DB::table('employees')->insert([
                    'name' => "Sekretariat {$i} {$start}-{$end}",
                    'position' => 'Sekretariat',
                    'start_year' => $start,
                    'end_year' => $end,
                    'email' => "sekretariat{$i}{$start}@example.test",
                    'photo' => $avatars[array_rand($avatars)],
                    'facebook' => "https://facebook.com/sekretariat{$i}{$start}",
                    'instagram' => "https://instagram.com/sekretariat{$i}{$start}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

