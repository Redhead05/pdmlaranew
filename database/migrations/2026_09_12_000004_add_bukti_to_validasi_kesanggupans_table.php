<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('validasi_kesanggupans', function (Blueprint $table) {
            $table->longText('bukti')->nullable()->after('ttd');
        });
    }

    public function down(): void
    {
        Schema::table('validasi_kesanggupans', function (Blueprint $table) {
            $table->dropColumn('bukti');
        });
    }
};
