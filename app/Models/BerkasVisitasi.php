<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BerkasVisitasi extends Model
{
    protected $table = 'berkas_visitasi';

    protected $fillable = [
        'tahap_id',
        'lembaga_id',
        'user_id',
        'tanggal_visitasi',
        'jenis_perjalanan',
        'bukti_transport',
        'bukti_menginap',
        'foto_depan',
        'scan_sppd',
        'surat_perjalanan_dinas',
        'pakta_integritas',
        'berita_acara',
        'daftar_hadir',
        'nominal_transport',
        'nominal_menginap',
        'latitude',
        'longitude',
        'jarak_km',
        'status',
        'admin_komentar',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'tanggal_visitasi' => 'date',
        'latitude' => 'float',
        'longitude' => 'float',
        'jarak_km' => 'float',
        'nominal_transport' => 'integer',
        'nominal_menginap' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    public function tahap()
    {
        return $this->belongsTo(Tahap::class, 'tahap_id');
    }

    public function lembaga()
    {
        return $this->belongsTo(Lembaga::class, 'lembaga_id');
    }

    public function asesor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
