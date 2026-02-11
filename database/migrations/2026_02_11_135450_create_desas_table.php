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
        Schema::create('desas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_des')->primary();
            $table->unsignedBigInteger('id_kec');
            $table->string('nama');
            $table->decimal('long_des', 17, 11)->nullable();
            $table->decimal('lat_des', 17, 11)->nullable();
            $table->timestamps();

            $table->foreign('id_kec')->references('id_kec')->on('kecamatans')->cascadeOnDelete();
            $table->index('id_kec');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desas');
    }
};
