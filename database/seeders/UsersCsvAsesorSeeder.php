<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersCsvAsesorSeeder extends Seeder
{
    public function run(): void
    {
        Role::findOrCreate('asesor');

        $path = database_path('seeders/users.csv');
        if (! file_exists($path)) {
            $this->command?->error("File not found: {$path}");
            return;
        }

        $fp = fopen($path, 'rb');
        if ($fp === false) {
            $this->command?->error("Cannot open file: {$path}");
            return;
        }

        // Detect delimiter
        $firstLine = fgets($fp);
        rewind($fp);
        $delimiter = (strpos((string) $firstLine, ';') !== false) ? ';' : ',';

        $rawHeader = fgetcsv($fp, 0, $delimiter);
        if ($rawHeader === false) {
            fclose($fp);
            $this->command?->error('Empty or invalid CSV header.');
            return;
        }

        // strip BOM on first header cell
        $rawHeader[0] = preg_replace('/^\xEF\xBB\xBF/', '', $rawHeader[0]);
        $headers = array_map(static fn ($h) => strtolower(trim((string) $h)), $rawHeader);

        $inserted = 0;
        $updated = 0;
        $skipped = 0;

        try {
            while (($row = fgetcsv($fp, 0, $delimiter)) !== false) {
                if (count($row) < count($headers)) {
                    $row = array_pad($row, count($headers), '');
                } elseif (count($row) > count($headers)) {
                    $row = array_slice($row, 0, count($headers));
                }

                $data = array_combine($headers, $row);
                if ($data === false) {
                    $skipped++;
                    continue;
                }

                $nia = trim((string) ($data['nia'] ?? ''));
                $name = trim((string) ($data['name'] ?? ''));
                $email = trim((string) ($data['email'] ?? ''));

                if ($nia === '' || $name === '' || $email === '') {
                    $skipped++;
                    continue;
                }

                // CSV includes some demo users; skip them to avoid clashing with RoleSeeder
                if (in_array($email, ['admin@example.com', 'adminlanding@example.com', 'user@example.com'], true)) {
                    $skipped++;
                    continue;
                }

                $userAttrs = [
                    'name' => $name,
                    'nia' => $nia,
                    'email' => $email,
                    'is_active' => $this->toBool($data['is_active'] ?? true),
                    // password in CSV is blank; set default
                    'password' => Hash::make('password'),
                ];

                // Slug: pakai dari CSV jika tersedia & unik, kalau tidak maka generate random 7 digit unik
                $csvSlug = trim((string) ($data['slug'] ?? ''));
                if ($csvSlug !== '' && ! User::query()->where('slug', $csvSlug)->exists()) {
                    $userAttrs['slug'] = $csvSlug;
                } else {
                     $userAttrs['slug'] = $this->generateUniqueNumericSlug();
                }

                $user = User::query()->where('email', $email)->first();
                if ($user) {
                    // Penting: jangan bikin slug berubah-ubah tiap seeder dijalankan
                    // Jika user sudah punya slug, pertahankan.
                    if (! empty($user->slug)) {
                        unset($userAttrs['slug']);
                    }

                    $user->fill($userAttrs);
                    $user->save();
                    $updated++;
                } else {
                    // avoid collisions on nia as it's unique
                    if (User::query()->where('nia', $nia)->exists()) {
                        $skipped++;
                        continue;
                    }

                    // kalau CSV slug di-skip karena bentrok, generate slug baru lagi agar aman
                    if (isset($userAttrs['slug']) && User::query()->where('slug', $userAttrs['slug'])->exists()) {
                        $userAttrs['slug'] = $this->generateUniqueNumericSlug();
                    }

                    $user = User::query()->create($userAttrs);
                    $inserted++;
                }

                if (! $user->hasRole('asesor')) {
                    $user->assignRole('asesor');
                }

                $detailAttrs = [
                    'address_home' => $this->nullIfEmpty($data['address_home'] ?? null),
                    'home_city' => $this->nullIfEmpty($data['home_city'] ?? null),
                    'address_work' => $this->nullIfEmpty($data['address_work'] ?? null),
                    'work_city' => $this->nullIfEmpty($data['work_city'] ?? null),
                    'gender' => $this->nullIfEmpty($data['gender'] ?? null),
                    'type_asesor' => $this->nullIfEmpty($data['type_asesor'] ?? null),
                    'unit_kerja' => $this->nullIfEmpty($data['unit_kerja'] ?? null),
                    'date_born' => $this->parseDate($data['tgllahir'] ?? null),
                    'ktp' => $this->nullIfEmpty($data['ktp'] ?? null),
                    'phone' => $this->nullIfEmpty($data['hp'] ?? null),
                    // CSV header is 'lintas'
                    'lintas_rumpun' => $this->toBoolNullable($data['lintas'] ?? null),

                    // Coordinates from CSV
                    'latitude' => $this->toDecimalNullable($data['latitude'] ?? null),
                    'longitude' => $this->toDecimalNullable($data['longitude'] ?? null),
                    'location_enabled' => $this->toBoolNullable($data['location_enabled'] ?? null),
                ];

                // don't overwrite existing with null/empty
                $detailAttrs = array_filter(
                    $detailAttrs,
                    static fn ($v) => $v !== null && $v !== ''
                );

                UserDetail::query()->updateOrCreate(
                    ['user_id' => $user->id],
                    $detailAttrs
                );
            }

            $this->command?->info("UsersCsvAsesorSeeder: Inserted {$inserted}, Updated {$updated}, Skipped {$skipped}");
        } finally {
            fclose($fp);
        }
    }

    private function generateUniqueNumericSlug(): string
    {
        do {
            $slug = (string) random_int(1000000, 9999999);
        } while (User::query()->where('slug', $slug)->exists());

        return $slug;
    }

    private function nullIfEmpty($v): ?string
    {
        $v = is_string($v) ? trim($v) : $v;
        return ($v === null || $v === '') ? null : (string) $v;
    }

    private function parseDate($v): ?string
    {
        $v = is_string($v) ? trim($v) : $v;
        if ($v === null || $v === '') {
            return null;
        }

        $text = str_replace('/', '-', (string) $v);
        $ts = strtotime($text);
        return $ts ? date('Y-m-d', $ts) : null;
    }

    private function toBool($v): bool
    {
        if (is_bool($v)) {
            return $v;
        }

        $v = strtolower(trim((string) $v));
        return in_array($v, ['1', 'true', 'yes', 'y', 'on'], true);
    }

    private function toBoolNullable($v): ?bool
    {
        $v = is_string($v) ? trim($v) : $v;
        return ($v === null || $v === '') ? null : $this->toBool($v);
    }

    private function toDecimalNullable($v): ?float
    {
        $v = is_string($v) ? trim($v) : $v;
        if ($v === null || $v === '') {
            return null;
        }

        $v = str_replace("\xc2\xa0", '', (string) $v); // NBSP
        $v = str_replace(',', '.', (string) $v);
        $v = preg_replace('/[^\d\.\-]/', '', (string) $v);
        if ($v === '' || $v === '-' || $v === '.') {
            return null;
        }

        return (float) $v;
    }
}

