<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsDetail extends Model
{
    use SoftDeletes;

    protected $table = 'news_details';

    protected $fillable = [
        'news_id',
        'description',
        'thumbnail',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id', 'id');
    }
}
