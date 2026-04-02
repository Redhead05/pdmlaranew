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
            $table->foreignId('tahap_id')
                ->constrained('tahaps')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // null = belum mengisi, false = tidak, true = ya
            $table->boolean('kesediaan')->nullable()->default(null);
            $table->integer('kesanggupan')->nullable();
            $table->longText('alasan')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'tahap_id']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('kesanggupans');
    }
};
