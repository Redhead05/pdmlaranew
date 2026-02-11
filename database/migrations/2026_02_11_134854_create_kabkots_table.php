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
        Schema::create('kabkots', function (Blueprint $table) {
            $table->unsignedBigInteger('idkabkot')->primary();
            $table->string('nama_kabkot');
            $table->decimal('long_kabkot', 17, 11)->nullable();
            $table->decimal('lat_kabkot', 17, 11)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kabkots');
    }
};
