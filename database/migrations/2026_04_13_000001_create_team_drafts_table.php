<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('team_drafts')) {
            return;
        }

        Schema::create('team_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('run_id')->constrained('team_generation_runs')->onDelete('cascade');
            $table->string('team_code')->nullable();
            $table->timestamps();

            $table->unique(['run_id', 'team_code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_drafts');
    }
};
