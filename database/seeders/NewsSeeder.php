<?php
// File: database/seeders/NewsSeeder.php
namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        $userIds = User::pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();

        for ($i = 1; $i <= 15; $i++) {
            $title = $faker->sentence(rand(3, 6));
            $slug = Str::slug($title);
            $original = $slug;
            $k = 1;

            // ensure unique slug even if soft-deleted exist
            while (News::withTrashed()->where('slug', $slug)->exists()) {
                $slug = $original . '-' . $k++;
            }

            $news = News::create([
                'title' => $title,
                'slug' => $slug,
                'is_active' => $faker->boolean(80),
                'category_id' => count($categoryIds) ? $faker->randomElement($categoryIds) : null,
                'created_by' => count($userIds) ? $faker->randomElement($userIds) : null,
            ]);

            $thumbnail = $faker->boolean(60) ? 'news_thumbnails/sample_' . $i . '.jpg' : null;

            $news->detail()->create([
                'description' => $faker->paragraphs(rand(2, 6), true),
                'thumbnail' => $thumbnail,
            ]);
        }
    }
}
