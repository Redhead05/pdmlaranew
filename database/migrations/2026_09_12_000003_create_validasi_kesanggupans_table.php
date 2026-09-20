<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('validasi_kesanggupans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('validasi_id')->constrained('validasis')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('kesediaan')->nullable(); // null = belum diisi, true = ya/bisa, false = tidak
            $table->text('alasan')->nullable();
            $table->longText('ttd')->nullable(); // base64 tanda tangan
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['validasi_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('validasi_kesanggupans');
    }
};
