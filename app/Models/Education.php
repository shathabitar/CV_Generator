<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $fillable = [
        'degree',
        'institution',
        'year',
    ];

    protected $table = 'educations';
    
    /** @use HasFactory<\Database\Factories\EducationFactory> */
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class, 'education_user');
    }
}
