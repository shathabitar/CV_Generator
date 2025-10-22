<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'position',
        'company',
        'start_date',
        'end_date',
        'description',
    ];
    /** @use HasFactory<\Database\Factories\ExperienceFactory> */
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
