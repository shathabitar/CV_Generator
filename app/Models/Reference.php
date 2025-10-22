<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable = [
        'name',
        'company',
        'phone_number',
        'email',
    ];
    /** @use HasFactory<\Database\Factories\ReferenceFactory> */
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
