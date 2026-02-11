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
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kec')->primary();
            $table->unsignedBigInteger('idkabkot');
            $table->string('nama');
            $table->decimal('long_kec', 17, 11)->nullable();
            $table->decimal('lat_kec', 17, 11)->nullable();
            $table->timestamps();

            $table->foreign('idkabkot')->references('idkabkot')->on('kabkots')->cascadeOnDelete();
            $table->index('idkabkot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatans');
    }
};
