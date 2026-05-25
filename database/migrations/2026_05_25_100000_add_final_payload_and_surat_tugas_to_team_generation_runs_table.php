<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('team_generation_runs', function (Blueprint $table) {
			$table->json('final_pairs_payload')->nullable()->after('finalized_at');
			$table->unsignedBigInteger('surat_tugas_generated_by')->nullable()->after('final_pairs_payload');
			$table->timestamp('surat_tugas_generated_at')->nullable()->after('surat_tugas_generated_by');
			$table->timestamp('surat_tugas_notification_sent_at')->nullable()->after('surat_tugas_generated_at');

			$table->foreign('surat_tugas_generated_by')
				->references('id')
				->on('users')
				->nullOnDelete();
		});
	}

	public function down(): void
	{
		Schema::table('team_generation_runs', function (Blueprint $table) {
			$table->dropForeign(['surat_tugas_generated_by']);
			$table->dropColumn([
				'final_pairs_payload',
				'surat_tugas_generated_by',
				'surat_tugas_generated_at',
				'surat_tugas_notification_sent_at',
			]);
		});
	}
};
