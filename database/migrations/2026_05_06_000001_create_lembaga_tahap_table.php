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
        if (Schema::hasTable('lembaga_tahap')) {
            return;
        }

        Schema::create('lembaga_tahap', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahap_id')->constrained('tahaps')->cascadeOnDelete();
            $table->foreignId('lembaga_id')->constrained('lembagas')->cascadeOnDelete();
            $table->timestamps();

            // ensure one lembaga only attached to a single tahap (per requirement)
            $table->unique('lembaga_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembaga_tahap');
    }
};

