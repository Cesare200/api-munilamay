<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decreto extends Model
{
    use HasFactory;

    protected $table = 'decretos';

    protected $fillable = [
        'numero',
        'anio',
        'fecha',
        'pdf',
        'status',
    ];

    protected $casts = [
        'fecha' => 'date',
        'status' => 'boolean',
        'anio' => 'integer',
    ];
}