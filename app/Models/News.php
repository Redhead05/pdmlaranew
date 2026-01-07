<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'is_active',
        'slug',
        'category_id',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function author()
    {
        return $this->HasMany(User::class, 'id', 'created_by');
    }
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
    public function detail()
    {
        return $this->hasOne(NewsDetail::class, 'news_id', 'id');
    }
}
