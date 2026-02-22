<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            if (!Schema::hasColumn('user_details', 'location_enabled')) {
                $table->boolean('location_enabled')->default(false)->after('longitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            if (Schema::hasColumn('user_details', 'location_enabled')) {
                $table->dropColumn('location_enabled');
            }
        });
    }
};

