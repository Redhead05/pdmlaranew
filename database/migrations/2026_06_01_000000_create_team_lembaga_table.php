h artisan serve<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pivot hasil pairing tim asesor <-> lembaga per tahap.
     * Satu lembaga hanya boleh untuk satu tim dalam satu tahap.
     */
    public function up(): void
    {
        Schema::create('team_lembaga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahap_id')->constrained('tahaps')->cascadeOnDelete();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('lembaga_id')->constrained('lembagas')->cascadeOnDelete();
            $table->decimal('distance_km', 10, 3)->nullable();
            $table->boolean('is_manual')->default(false);
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['tahap_id', 'team_id', 'lembaga_id']);
            $table->unique(['tahap_id', 'lembaga_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_lembaga');
    }
};
