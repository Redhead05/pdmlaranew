<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Validasi extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'surat_keputusan',
        'slug',
        'start_date',
        'end_date',
        'pairing_locked_at',
        'pairing_locked_by',
        'surat_tugas_payload',
        'surat_tugas_number',
        'surat_tugas_slug',
        'surat_tugas_generated_by',
        'surat_tugas_generated_at',
        'surat_tugas_notification_sent_at',
        'surat_tugas_history',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'pairing_locked_at' => 'datetime',
        'surat_tugas_generated_at' => 'datetime',
        'surat_tugas_notification_sent_at' => 'datetime',
        'surat_tugas_payload' => 'array',
        'surat_tugas_history' => 'array',
    ];

    public function kesanggupans(): HasMany
    {
        return $this->hasMany(ValidasiKesanggupan::class, 'validasi_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $base = Str::slug($model->nama).'-'.now()->format('YmdHis');
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base.'-'.$i++;
                }
                $model->slug = $slug;
            }
        });
    }
}
