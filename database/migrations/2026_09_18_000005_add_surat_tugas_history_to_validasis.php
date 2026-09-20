<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('validasis', function (Blueprint $table) {
            $table->json('surat_tugas_history')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('validasis', function (Blueprint $table) {
            $table->dropColumn('surat_tugas_history');
        });
    }
};
