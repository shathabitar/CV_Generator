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

    // Many-to-many relationship with skills
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'skill_user'); 
        // 'skill_user' is the pivot table (user_id, skill_id)
    }

    // Many-to-many relationship with education
   public function educations()
{
    return $this->belongsToMany(Education::class, 'education_user'); 

}

    // Many-to-many relationship with experience
    public function experiences()
    {
        return $this->belongsToMany(Experience::class, 'experience_user');
    }

    // Many-to-many relationship with references
    public function references()
    {
        return $this->belongsToMany(Reference::class, 'reference_user');
    }

    // Many-to-many relationship with certificates
    public function certificates()
    {
        return $this->belongsToMany(Certificate::class, 'certificate_user');
    }
}
