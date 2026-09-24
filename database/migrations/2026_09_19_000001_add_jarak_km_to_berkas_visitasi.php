<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('berkas_visitasi', function (Blueprint $table) {
            $table->decimal('jarak_km', 10, 2)->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('berkas_visitasi', function (Blueprint $table) {
            $table->dropColumn('jarak_km');
        });
    }
};
