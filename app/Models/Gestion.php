<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    use HasFactory;

    protected $table = 'gestion';

    protected $fillable = [
        'alcalde',
        'eslogan',
        'concejo',
        'mision',
        'vision',
        'fotos_gestion',
        'historia',
    ];

    protected $casts = [
        'alcalde' => 'array',
        'concejo' => 'array',
        'fotos_gestion' => 'array',
    ];
}