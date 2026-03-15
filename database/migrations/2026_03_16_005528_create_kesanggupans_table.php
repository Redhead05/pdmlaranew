<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kesanggupans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahap_id')->constrained('tahaps')->onDelete('cascade');
            $table->boolean('kesediaan')->default(false); // false = tidak, true = ya
            $table->unsignedTinyInteger('kesanggupan')->comment('Allowed values: 2..6');
            $table->text('alasan')->nullable();
            $table->timestamps();

            $table->index('tahap_id');
        });

        // Optional DB-level check constraint to enforce 2..6 (MySQL 8+/Postgres)
        // If your DB does not support check constraints, remove the statement.
        DB::statement("ALTER TABLE kesanggupans ADD CONSTRAINT chk_kesanggupan_range CHECK (kesanggupan BETWEEN 2 AND 6)");
    }

    public function down(): void
    {
        Schema::dropIfExists('kesanggupans');
    }
};
