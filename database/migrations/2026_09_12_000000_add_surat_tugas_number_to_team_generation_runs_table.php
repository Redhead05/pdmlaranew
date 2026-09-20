<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_generation_runs', function (Blueprint $table) {
            // Nomor surat tugas yang diinput admin saat "Kirim Surat Tugas".
            $table->string('surat_tugas_number')->nullable()->after('surat_tugas_notification_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('team_generation_runs', function (Blueprint $table) {
            $table->dropColumn('surat_tugas_number');
        });
    }
};
