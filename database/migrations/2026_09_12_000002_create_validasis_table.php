<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('validasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('surat_keputusan')->nullable();
            $table->string('slug')->unique();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->timestamp('pairing_locked_at')->nullable();
            $table->unsignedBigInteger('pairing_locked_by')->nullable();
            $table->json('surat_tugas_payload')->nullable();
            $table->string('surat_tugas_number')->nullable();
            $table->string('surat_tugas_slug')->nullable();
            $table->unsignedBigInteger('surat_tugas_generated_by')->nullable();
            $table->timestamp('surat_tugas_generated_at')->nullable();
            $table->timestamp('surat_tugas_notification_sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pairing_locked_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('surat_tugas_generated_by')->references('id')->on('users')->nullOnDelete();
            $table->index('surat_tugas_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('validasis');
    }
};
