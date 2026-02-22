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
            if (!Schema::hasColumn('user_details', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('type_asesor');
            }
            if (!Schema::hasColumn('user_details', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            if (Schema::hasColumn('user_details', 'longitude')) {
                $table->dropColumn('longitude');
            }
            if (Schema::hasColumn('user_details', 'latitude')) {
                $table->dropColumn('latitude');
            }
        });
    }
};

