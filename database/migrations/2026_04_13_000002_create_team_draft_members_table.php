<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('team_draft_members')) {
            return;
        }

        Schema::create('team_draft_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('run_id')->constrained('team_generation_runs')->onDelete('cascade');
            $table->foreignId('team_draft_id')->constrained('team_drafts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_manual')->default(false);
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();

            $table->unique(['run_id', 'user_id']);
            $table->unique(['team_draft_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_draft_members');
    }
};

