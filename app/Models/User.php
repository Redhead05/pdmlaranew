<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'nia',
        'email',
        'password',
        'is_active',
        'slug',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // include deleted_at for soft delete
    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    //Relationship
    public function detail(): HasOne
    {
        return $this->hasOne(UserDetail::class);
    }
    public function organizationStructures()
    {
        return $this->hasMany(\App\Models\OrganizationStructure::class);
    }

    public function kesanggupans(): HasMany
    {
        return $this->hasMany(Kesanggupan::class);
    }

    /**
     * Certifications (one-to-many)
     */
    public function certifications(): HasMany
    {
        return $this->hasMany(Certification::class);
    }

    // Use slug for route model binding
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
