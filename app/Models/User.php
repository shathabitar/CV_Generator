<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'about',
        'photo',
    ];

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'skill_user'); 
    }

   public function educations()
    {
        return $this->belongsToMany(Education::class, 'education_user'); 
    }

    public function experiences()
    {
        return $this->belongsToMany(Experience::class, 'experience_user');
    }

    public function references()
    {
        return $this->belongsToMany(Reference::class, 'reference_user');
    }

    public function certificates()
    {
        return $this->belongsToMany(Certificate::class, 'certificate_user');
    }
}
