<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Gallery extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'image',
        'description',
        'is_active',
        'category_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Accessor: image_url
     * Mengembalikan URL yang siap dipakai di frontend.
     */
    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return asset('assets/logo_BANPDMJATIM.png');
        }

        // already a full URL
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            $url = $this->image;

            // jika sudah berupa direct googleusercontent link, kembalikan langsung
            if (Str::contains($url, 'drive.googleusercontent.com')) {
                return $url;
            }

            // convert Google Drive share link ke direct view/embed link bila memungkinkan
            if (Str::contains($url, 'drive.google.com')) {
                $id = null;

                // 1) /d/FILE_ID style (mis. /file/d/FILE_ID/view)
                if (preg_match('#/d/([a-zA-Z0-9_-]+)#', $url, $m) && !empty($m[1])) {
                    $id = $m[1];
                }

                // 2) ?id=FILE_ID style (mis. open?id=FILE_ID atau uc?id=FILE_ID)
                if (!$id) {
                    $parts = parse_url($url);
                    if (!empty($parts['query'])) {
                        parse_str($parts['query'], $qs);
                        if (!empty($qs['id'])) {
                            $id = $qs['id'];
                        }
                    }
                }

                // 3) thumbnail?id=FILE_ID or other forms, coba ambil id lewat regex id=(...)
                if (!$id && preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $url, $m2) && !empty($m2[1])) {
                    $id = $m2[1];
                }

                if ($id) {
                    // jika kategori ter-relasi dan bertipe video, return embed preview agar bisa ditampilkan di lightbox/iframe
                    $isVideo = optional($this->category)->name === 'video';
                    if ($isVideo) {
                        return 'https://drive.google.com/file/d/' . $id . '/preview';
                    }

                    // default: return direct view untuk gambar
                    return 'https://drive.google.com/uc?export=view&id=' . $id;
                }

                // fallback: kembalikan original (mungkin link sudah direct preview)
                return $url;
            }

            return $url;
        }

        // assume it's a storage path
        return asset('storage/' . ltrim($this->image, '/'));
    }

    /**
     * Helper: apakah image berformat external URL
     */
    public function isExternal(): bool
    {
        return Str::startsWith($this->image, ['http://', 'https://']);
    }
}
