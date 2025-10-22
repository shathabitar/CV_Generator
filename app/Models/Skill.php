<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'skill_name',
        'type', 
    ];
    /** @use HasFactory<\Database\Factories\SkillFactory> */
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
