<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lembaga_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_lembaga')->constrained('lembagas')->onDelete('cascade');

            // Atribut Khusus PAUD
            $table->string('cek_akreditasi')->nullable();
            $table->date('habis_masa_berlaku')->nullable();
            $table->string('cek_sasaran_2025')->nullable();

            // Atribut Khusus Kesetaraan Gabungan
            $table->boolean('has_paket_a')->default(false);
            $table->boolean('has_paket_b')->default(false);
            $table->boolean('has_paket_c')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembaga_details');
    }
};
