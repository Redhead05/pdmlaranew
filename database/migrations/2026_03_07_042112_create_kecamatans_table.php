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
            $table->id();
            // foreign key ke kabkots (id)
            $table->foreignId('kabkot_id')->constrained('kabkots')->onDelete('cascade');
            $table->string('nama_kec');
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
        Schema::dropIfExists('kecamatans');
    }
};
