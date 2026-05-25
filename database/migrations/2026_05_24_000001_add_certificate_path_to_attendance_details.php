<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('attendance_details', function (Blueprint $table) {
			if (! Schema::hasColumn('attendance_details', 'certificate_path')) {
				$table->string('certificate_path')->nullable()->after('signature');
			}
		});
	}

	public function down(): void
	{
		Schema::table('attendance_details', function (Blueprint $table) {
			if (Schema::hasColumn('attendance_details', 'certificate_path')) {
				$table->dropColumn('certificate_path');
			}
		});
	}
};
