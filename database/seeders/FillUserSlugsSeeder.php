<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class FillUserSlugsSeeder extends Seeder
{
    private function uniqueNumericSlug(): string
    {
        do {
            $slug = (string) random_int(1000000, 9999999);
        } while (User::where('slug', $slug)->exists());

        return $slug;
    }

    public function run(): void
    {
        User::query()
            ->whereNull('slug')
            ->orWhere('slug', '')
            ->orderBy('id')
            ->each(function (User $user) {
                $user->slug = $this->uniqueNumericSlug();
                $user->save();
            });
    }
}

