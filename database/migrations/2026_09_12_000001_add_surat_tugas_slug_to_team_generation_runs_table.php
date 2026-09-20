<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_generation_runs', function (Blueprint $table) {
            // Slug aman untuk URL surat tugas asesor: nomor ST + timestamp.
            $table->string('surat_tugas_slug')->nullable()->after('surat_tugas_number');
            $table->index('surat_tugas_slug');
        });
    }

    public function down(): void
    {
        Schema::table('team_generation_runs', function (Blueprint $table) {
            $table->dropIndex(['surat_tugas_slug']);
            $table->dropColumn('surat_tugas_slug');
        });
    }
};
