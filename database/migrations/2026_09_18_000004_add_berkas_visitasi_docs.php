<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('berkas_visitasi', function (Blueprint $table) {
            $table->string('scan_sppd')->nullable();
            $table->string('surat_perjalanan_dinas')->nullable();
            $table->string('pakta_integritas')->nullable();
            $table->string('berita_acara')->nullable();
            $table->string('daftar_hadir')->nullable();
            $table->unsignedBigInteger('nominal_transport')->nullable();
            $table->unsignedBigInteger('nominal_menginap')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('berkas_visitasi', function (Blueprint $table) {
            $table->dropColumn(['scan_sppd', 'surat_perjalanan_dinas', 'pakta_integritas', 'berita_acara', 'daftar_hadir', 'nominal_transport', 'nominal_menginap']);
        });
    }
};
