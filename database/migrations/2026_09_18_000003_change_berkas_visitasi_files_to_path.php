<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('berkas_visitasi', function (Blueprint $table) {
            $table->string('bukti_transport')->change();
            $table->string('bukti_menginap')->nullable()->change();
            $table->string('foto_depan')->change();
        });
    }

    public function down(): void
    {
        Schema::table('berkas_visitasi', function (Blueprint $table) {
            $table->longText('bukti_transport')->change();
            $table->longText('bukti_menginap')->nullable()->change();
            $table->longText('foto_depan')->change();
        });
    }
};
