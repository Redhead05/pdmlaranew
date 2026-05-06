<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tahaps', function (Blueprint $table) {
            if (! Schema::hasColumn('tahaps', 'slug')) {
                $table->string('slug')->unique()->nullable()->after('id');
            }
        });

        // populate slug for existing tahaps
        $tahaps = DB::table('tahaps')->get();
        foreach ($tahaps as $t) {
            if (empty($t->slug)) {
                $slug = Str::slug(($t->tahap ?? 'tahap') . '-' . ($t->id ?? Str::random(6)));
                // ensure uniqueness by appending id
                $slug = $slug ?: ('tahap-' . ($t->id ?? Str::random(6)));
                DB::table('tahaps')->where('id', $t->id)->update(['slug' => $slug]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tahaps', function (Blueprint $table) {
            if (Schema::hasColumn('tahaps', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
