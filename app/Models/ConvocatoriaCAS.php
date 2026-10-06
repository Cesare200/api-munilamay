<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConvocatoriaCAS extends Model // <-- CORREGIDO EN SINGULAR
{
    // Forzamos el nombre exacto de la tabla de tu migración
    protected $table = 'convocatorias__c_a_s';

    protected $fillable = [
        'codigo_cas',
        'proceso',
        'anio',
        'fecha',
        'tipo',
        'titulo',
        'pdf',
        'status'
    ];
}
