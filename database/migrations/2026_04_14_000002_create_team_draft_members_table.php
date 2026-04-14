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
            $table->unsignedBigInteger('run_id');
            $table->unsignedBigInteger('team_draft_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_manual')->default(false);
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();

            $table->foreign('run_id')->references('id')->on('team_generation_runs')->onDelete('cascade');
            $table->foreign('team_draft_id')->references('id')->on('team_drafts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['run_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_draft_members');
    }
};
