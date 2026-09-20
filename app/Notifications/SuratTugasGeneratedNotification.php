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
        protected string $nomorSt,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Surat Tugas Tahap '.$this->tahap->tahap.' Diterbitkan',
            'message' => 'Surat tugas SK '.$this->tahap->tahap.' ('.$this->nomorSt.') telah diterbitkan. Tim Anda: '.$this->teamCode.'.',
            'team_code' => $this->teamCode,
            'nomor_st' => $this->nomorSt,
            'tahap_id' => $this->tahap->id,
            'tahap_name' => $this->tahap->tahap,
            'tahap_slug' => $this->tahap->slug,
            'run_id' => $this->run->id,
            'action_url' => route('asesor.surat-tugas.show', [
                'tahap' => $this->tahap->slug,
                'st' => $this->run->surat_tugas_slug,
            ]),
        ];
    }
}
