<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocInteres extends Model
{
    use HasFactory;

    protected $table = 'docinteres';

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha',
        'pdf',
        'status',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];
}