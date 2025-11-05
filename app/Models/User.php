<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'about',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Optimized relationships with proper return types
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'skill_user'); 
    }

    public function educations(): BelongsToMany
    {
        return $this->belongsToMany(Education::class, 'education_user'); 
    }

    public function experiences(): BelongsToMany
    {
        return $this->belongsToMany(Experience::class, 'experience_user');
    }

    public function references(): BelongsToMany
    {
        return $this->belongsToMany(Reference::class, 'reference_user');
    }

    public function certificates(): BelongsToMany
    {
        return $this->belongsToMany(Certificate::class, 'certificate_user');
    }

    // Scope for loading all CV-related data efficiently
    public function scopeWithCvData($query)
    {
        return $query->with([
            'educations',
            'experiences',
            'skills',
            'references',
            'certificates'
        ]);
    }
}
