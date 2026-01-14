<?php
// php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\Category;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // ensure categories exist
        $photoCategory = Category::firstOrCreate(['name' => 'photo'], ['slug' => 'photo']);
        $videoCategory = Category::firstOrCreate(['name' => 'video'], ['slug' => 'video']);

        // desired filename (will be stored in DB as 'galleries/filename')
        $filename = '30mMXN1rSbUa0qYyHVHJdxyQfTcDcVdE7217Je4d.png';
        $destPath = 'galleries/' . $filename;

        // source absolute path on your Windows machine
        $source = 'C:\\Users\\WINDOWS\\Herd\\pdmlaranew\\public\\storage\\galleries\\' . $filename;

        // ensure destination directory exists
        if (! Storage::disk('public')->exists('galleries')) {
            Storage::disk('public')->makeDirectory('galleries');
        }

        // if source exists, copy it; otherwise create a tiny transparent PNG as fallback
        if (File::exists($source)) {
            Storage::disk('public')->put($destPath, File::get($source));
        } else {
            // 1x1 transparent PNG (base64)
            $tinyPng = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGNgYAAAAAMAASsJTYQAAAAASUVORK5CYII=');
            Storage::disk('public')->put($destPath, $tinyPng);
        }

        // create 15 gallery records using the same image filename
        for ($i = 1; $i <= 15; $i++) {
            $title = "Seeder Item #{$i}";
            Gallery::create([
                'title' => $title,
                'slug' => Str::slug($title) . '-' . time() . $i,
                'image' => $destPath, // store `galleries/2kaTy3...png`
                'description' => $faker->sentence(),
                'is_active' => $faker->boolean(80),
                'category_id' => $photoCategory->id,
            ]);
        }
    }
}
