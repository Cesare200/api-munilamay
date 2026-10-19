<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenamientoTerritorial extends Model
{
    use HasFactory;

    protected $table = 'ordenamiento_territorials';

    protected $fillable = [
        'titulo',
        'categoria',
        'subcategoria',
        'descripcion',
        'archivo_pdf',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
}