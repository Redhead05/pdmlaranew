<?php

namespace App\Notifications;

use App\Models\Validasi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ValidasiDibuatNotification extends Notification
{
    use Queueable;

    public function __construct(protected Validasi $validasi)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Validasi '.$this->validasi->nama.' Dibuka',
            'message' => 'Form validasi "'.$this->validasi->nama.'" telah dibuka. Silakan isi keterangan Ya/Tidak beserta tanda tangan Anda.',
            'validasi_id' => (string) $this->validasi->id,
            'validasi_name' => $this->validasi->nama,
            'action_url' => route('asesor.validasi.index'),
        ];
    }
}
