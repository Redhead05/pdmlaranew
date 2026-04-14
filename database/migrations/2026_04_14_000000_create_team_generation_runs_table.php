<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('team_generation_runs')) {
            return;
        }

        Schema::create('team_generation_runs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tahap_id');
            $table->string('status', 20)->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('finalized_by')->nullable();
            $table->timestamp('finalized_at')->nullable();
            $table->timestamps();

            $table->foreign('tahap_id')->references('id')->on('tahaps')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_generation_runs');
    }
};
