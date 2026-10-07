<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstrumentoGestion extends Model
{
    use HasFactory;

    protected $table = 'instrumentos_gestion';

    protected $fillable = [
        'tipo',
        'nombre',
        'anio',
        'descripcion',
        'fecha',
        'aprobado_por',
        'pdf',
        'status',
    ];

    protected $casts = [
        'fecha' => 'date',
        'anio' => 'integer',
    ];
}