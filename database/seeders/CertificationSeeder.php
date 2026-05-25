<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Certification;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CertificationSeeder extends Seeder
{
    public function run()
    {
        // Ensure public disk folder exists
        Storage::disk('public')->makeDirectory('certificates');

        $asesors = User::role('asesor')->get();
        if ($asesors->isEmpty()) return;

        $years = [now()->year, now()->subYear()->year, now()->subYears(2)->year];

        foreach ($asesors as $asesor) {
            // Randomly decide 0..3 certs per user
            $count = rand(0,3);
            for ($i=0;$i<$count;$i++) {
                $year = $years[array_rand($years)];
                $issued = Carbon::create($year, rand(1,12), rand(1,28));
                $filename = 'certificates/'.$asesor->id.'/cert-'.$year.'-'.Str::random(6).'.pdf';
                Storage::disk('public')->put($filename, "Sample certificate for user {$asesor->id} year {$year}");

                Certification::create([
                    'user_id' => $asesor->id,
                    'title' => 'Sertifikat ' . Str::upper(Str::random(4)),
                    'certificate_number' => 'CERT-'.$asesor->id.'-'.$year.'-'.Str::random(4),
                    'issuer' => 'Lembaga Contoh',
                    'issued_at' => $issued,
                    'year' => $year,
                    'expires_at' => $issued->copy()->addYear(),
                    'file_path' => $filename,
                    'status' => 'valid',
                ]);
            }
        }
    }
}

