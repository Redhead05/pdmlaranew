<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add running/done to enum values so controller can set intermediate statuses
        DB::statement("ALTER TABLE `team_generation_runs` MODIFY `status` ENUM('draft','running','final','done') NOT NULL DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // revert to original enum (draft, final)
        DB::statement("ALTER TABLE `team_generation_runs` MODIFY `status` ENUM('draft','final') NOT NULL DEFAULT 'draft'");
    }
};
