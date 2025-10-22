<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'title',
        'company',
        'date',
    ];
    /** @use HasFactory<\Database\Factories\CertificateFactory> */
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
