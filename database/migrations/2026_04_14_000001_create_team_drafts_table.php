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
            $table->unsignedBigInteger('run_id');
            $table->string('team_code')->nullable();
            $table->timestamps();

            $table->foreign('run_id')->references('id')->on('team_generation_runs')->onDelete('cascade');
            $table->unique(['run_id', 'team_code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_drafts');
    }
};
