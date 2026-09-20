<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pengunci pairing tim<->lembaga pada tahap.
     * Saat pairing_locked_at terisi, seluruh mutasi pairing (generate,
     * assign, unassign, upload) ditolak sampai admin membuka kunci kembali.
     */
    public function up(): void
    {
        Schema::table('tahaps', function (Blueprint $table) {
            $table->timestamp('pairing_locked_at')->nullable()->after('end_date');
            $table->unsignedBigInteger('pairing_locked_by')->nullable()->after('pairing_locked_at');

            $table->foreign('pairing_locked_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tahaps', function (Blueprint $table) {
            $table->dropForeign(['pairing_locked_by']);
            $table->dropColumn(['pairing_locked_at', 'pairing_locked_by']);
        });
    }
};
