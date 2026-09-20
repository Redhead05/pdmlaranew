<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('validasi_lembaga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('validasi_id')->constrained('validasis')->cascadeOnDelete();
            $table->foreignId('lembaga_id')->constrained('lembagas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // asesor "bisa"
            $table->timestamps();

            $table->unique(['validasi_id', 'lembaga_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('validasi_lembaga');
    }
};
