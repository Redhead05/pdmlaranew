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
            $table->id();
            $table->string('nama_kabkot');
            // latitude & longitude - gunakan decimal supaya tepat (contoh presisi 10,7)
            $table->decimal('latitude', 14, 11)->nullable();
            $table->decimal('longitude', 14, 11)->nullable();
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
