<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Override asesor per baris pairing (per NPSN):
     *  - asesor_a_user_id / asesor_b_user_id: asesor yang ditetapkan admin untuk
     *    baris ini (null = pakai anggota tim sesuai urutan).
     *  - team_id dibuat nullable: baris yang asesornya tidak punya tim menjadi
     *    "tanpa tim" sampai admin menetapkan tim/asesor berikutnya.
     */
    public function up(): void
    {
        Schema::table('team_lembaga', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->nullable()->change();
            $table->unsignedBigInteger('asesor_a_user_id')->nullable()->after('is_manual');
            $table->unsignedBigInteger('asesor_b_user_id')->nullable()->after('asesor_a_user_id');

            $table->foreign('asesor_a_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('asesor_b_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('team_lembaga', function (Blueprint $table) {
            $table->dropForeign(['asesor_a_user_id']);
            $table->dropForeign(['asesor_b_user_id']);
            $table->dropColumn(['asesor_a_user_id', 'asesor_b_user_id']);
            $table->unsignedBigInteger('team_id')->nullable(false)->change();
        });
    }
};
