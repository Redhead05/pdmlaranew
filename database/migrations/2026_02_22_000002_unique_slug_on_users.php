<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UniqueSlugOnUsers extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backfill slug for existing users that don't have one
        $users = DB::table('users')->whereNull('slug')->orWhere('slug', '')->select('id')->get();

        foreach ($users as $u) {
            do {
                $slug = (string) random_int(1000000, 9999999);
            } while (DB::table('users')->where('slug', $slug)->exists());

            DB::table('users')->where('id', $u->id)->update(['slug' => $slug]);
        }

        // We avoid changing column nullability here to stay safe if doctrine/dbal is not available.
        // If you want to make slug NOT NULL, run an additional migration after ensuring all slugs populated
        // and having doctrine/dbal installed, then use: Schema::table('users', function (Blueprint $table) { $table->string('slug',32)->nullable(false)->change(); });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally clear slugs created by this migration (NOT recommended in production)
        // DB::table('users')->whereNotNull('slug')->update(['slug' => null]);
    }
}

