<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InteraccionMercado extends Model
{
    use HasFactory;

    protected $table = 'interaccion_mercado';

    protected $fillable = [
        'numero',
        'anio',
        'titulo',
        'descripcion',
        'fecha',
        'pdf',
        'status',
    ];

    protected $casts = [
        'fecha' => 'date',
        'anio' => 'integer',
    ];
}