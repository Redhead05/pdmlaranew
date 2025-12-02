<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_details', function (Blueprint $table) {
            // DROP foreign keys first to allow dropping/recreating indexes
            try {
                $table->dropForeign(['attendance_id']);
            } catch (\Throwable $e) {
                // ignore if not exists
            }

            try {
                $table->dropForeign(['user_id']);
            } catch (\Throwable $e) {
                // ignore if not exists
            }

            // drop unique index if exists
            try {
                $table->dropUnique(['attendance_id', 'user_id']);
            } catch (\Throwable $e) {
                // ignore
            }

            // make user_id nullable (requires doctrine/dbal if using change)
            try {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            } catch (\Throwable $e) {
                // If change() fails on some installations, leave as-is and migration can be adjusted manually.
            }

            // add json column for form data
            if (!Schema::hasColumn('attendance_details', 'form_data')) {
                $table->json('form_data')->nullable()->after('signature');
            }

            // re-create foreign keys (will create needed indexes)
            try {
                $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
            } catch (\Throwable $e) {
                // ignore
            }

            try {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            } catch (\Throwable $e) {
                // ignore
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendance_details', function (Blueprint $table) {
            // drop the foreign keys added in up()
            try {
                $table->dropForeign(['attendance_id']);
            } catch (\Throwable $e) {
                // ignore
            }

            try {
                $table->dropForeign(['user_id']);
            } catch (\Throwable $e) {
                // ignore
            }

            if (Schema::hasColumn('attendance_details', 'form_data')) {
                $table->dropColumn('form_data');
            }

            try {
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
            } catch (\Throwable $e) {
                // ignore
            }

            // restore unique index
            try {
                $table->unique(['attendance_id', 'user_id']);
            } catch (\Throwable $e) {
                // ignore
            }

            // re-add foreign keys
            try {
                $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
            } catch (\Throwable $e) {
                // ignore
            }

            try {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            } catch (\Throwable $e) {
                // ignore
            }
        });
    }
};
