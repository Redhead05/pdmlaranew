<?php

namespace App\Notifications;

use App\Models\Tahap;
use App\Models\TeamGenerationRun;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SuratTugasGeneratedNotification extends Notification
{
	use Queueable;

	public function __construct(
		protected Tahap $tahap,
		protected TeamGenerationRun $run,
		protected string $teamCode,
	) {
	}

	public function via(object $notifiable): array
	{
		return ['database'];
	}

	public function toArray(object $notifiable): array
	{
		return [
			'title' => 'Surat tugas asesor tersedia',
			'message' => 'Surat tugas untuk ' . $this->tahap->tahap . ' telah dibuat. Tim Anda: ' . $this->teamCode . '.',
			'team_code' => $this->teamCode,
			'tahap_id' => $this->tahap->id,
			'tahap_name' => $this->tahap->tahap,
			'run_id' => $this->run->id,
			'action_url' => route('asesor.surat-tugas.show', [
				'tahap' => $this->tahap->slug,
				'run' => $this->run->id,
			]),
		];
	}
}
