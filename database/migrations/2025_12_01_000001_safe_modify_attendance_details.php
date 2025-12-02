<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) add form_data if missing
        Schema::table('attendance_details', function (Blueprint $table) {
            if (!Schema::hasColumn('attendance_details', 'form_data')) {
                $table->json('form_data')->nullable()->after('signature');
            }
        });

        // 2) add temporary nullable column to hold user_id values
        Schema::table('attendance_details', function (Blueprint $table) {
            if (!Schema::hasColumn('attendance_details', 'user_id_tmp')) {
                $table->unsignedBigInteger('user_id_tmp')->nullable()->after('attendance_id');
            }
        });

        // 3) copy existing user_id -> user_id_tmp
        DB::statement('UPDATE attendance_details SET user_id_tmp = user_id');

        // 4) drop foreign keys and unique index so we can drop old user_id
        Schema::table('attendance_details', function (Blueprint $table) {
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
            try {
                $table->dropUnique(['attendance_id', 'user_id']);
            } catch (\Throwable $e) {
                // ignore
            }
        });

        // 5) drop old user_id column
        Schema::table('attendance_details', function (Blueprint $table) {
            if (Schema::hasColumn('attendance_details', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });

        // 6) recreate user_id as nullable and copy data back from tmp
        Schema::table('attendance_details', function (Blueprint $table) {
            if (!Schema::hasColumn('attendance_details', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('attendance_id');
            }
        });

        DB::statement('UPDATE attendance_details SET user_id = user_id_tmp');

        // 7) drop temporary column
        Schema::table('attendance_details', function (Blueprint $table) {
            if (Schema::hasColumn('attendance_details', 'user_id_tmp')) {
                $table->dropColumn('user_id_tmp');
            }
        });

        // 8) re-add foreign keys (attendance_id required)
        Schema::table('attendance_details', function (Blueprint $table) {
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
            if (Schema::hasColumn('attendance_details', 'form_data')) {
                $table->dropColumn('form_data');
            }
        });
    }
};

