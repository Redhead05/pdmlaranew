<?php

namespace App\Notifications;

use App\Models\Validasi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ValidasiSuratTugasNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Validasi $validasi,
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
            'title' => 'Surat Tugas Validasi '.$this->validasi->nama.' Diterbitkan',
            'message' => 'Surat tugas validasi ('.$this->nomorSt.') telah diterbitkan untuk Anda.',
            'nomor_st' => $this->nomorSt,
            'validasi_id' => (string) $this->validasi->id,
            'validasi_name' => $this->validasi->nama,
            'action_url' => route('asesor.validasi.surat-tugas', [
                'validasi' => $this->validasi->slug,
                'st' => $this->validasi->surat_tugas_slug,
            ]),
        ];
    }
}
