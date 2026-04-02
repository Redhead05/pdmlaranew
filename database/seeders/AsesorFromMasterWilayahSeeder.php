<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AsesorFromMasterWilayahSeeder extends Seeder
{
    public function run(): void
    {
        Role::findOrCreate('asesor');

        $faker = fake('id_ID');

        // kabkots columns based on model fillable: nama_kabkot, latitude, longitude
        $samples = DB::table('kabkots')
            ->whereNotNull('kabkots.latitude')
            ->whereNotNull('kabkots.longitude')
            ->inRandomOrder()
            ->limit(30)
            ->get([
                'kabkots.nama_kabkot as kabkot_name',
                'kabkots.latitude as latitude',
                'kabkots.longitude as longitude',
            ]);

        if ($samples->isEmpty()) {
            $this->command?->warn('No kabkot samples found with coordinates. Check kabkots columns (nama_kabkot, latitude, longitude).');
            return;
        }

        DB::transaction(function () use ($samples, $faker) {
            foreach ($samples as $i => $row) {
                do {
                    $slug = (string) random_int(1000000, 9999999);
                } while (User::query()->where('slug', $slug)->exists());

                $nia = sprintf('ASES-%04d', $i + 1);
                $email = 'asesor' . ($i + 1) . '@example.test';

                if (User::query()->where('email', $email)->exists()) {
                    continue;
                }

                $user = User::query()->create([
                    'name' => 'Asesor Sample ' . ($i + 1),
                    'nia' => $nia,
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'slug' => $slug,
                ]);

                UserDetail::query()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'gender' => ($i % 2 === 0) ? 'L' : 'P',
                        'address_home' => $faker->streetAddress(),
                        'home_city' => $row->kabkot_name,
                        'address_work' => $faker->streetAddress(),
                        'work_city' => $row->kabkot_name,
                        'type_asesor' => 'sampling',
                        'latitude' => (float) $row->latitude,
                        'longitude' => (float) $row->longitude,
                        'location_enabled' => true,
                    ]
                );

                $user->assignRole('asesor');
            }
        });
    }
}
