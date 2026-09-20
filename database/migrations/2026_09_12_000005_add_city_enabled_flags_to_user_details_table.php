<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->boolean('home_city_enabled')->default(false)->after('location_enabled');
            $table->boolean('work_city_enabled')->default(false)->after('home_city_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn(['home_city_enabled', 'work_city_enabled']);
        });
    }
};
