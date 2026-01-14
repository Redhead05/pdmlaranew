<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
    public function news()
    {
        return $this->hasMany(News::class);
    }
}
