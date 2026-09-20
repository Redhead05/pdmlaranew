<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berkas_visitasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahap_id')->constrained('tahaps')->cascadeOnDelete();
            $table->foreignId('lembaga_id')->constrained('lembagas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_visitasi');
            $table->string('jenis_perjalanan'); // pulang_pergi | menginap
            $table->longText('bukti_transport'); // base64
            $table->longText('bukti_menginap')->nullable(); // base64
            $table->longText('foto_depan'); // base64
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('status')->default('pending'); // pending | approved | rejected
            $table->text('admin_komentar')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berkas_visitasi');
    }
};
