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
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('certificate_number')->nullable();
            $table->string('issuer')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->smallInteger('year')->nullable()->index();
            $table->timestamp('expires_at')->nullable();
            $table->string('file_path')->nullable();
            $table->string('status')->default('valid');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'year']);
            $table->unique(['user_id', 'certificate_number'], 'user_certificate_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};

