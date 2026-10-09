<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcesoSeleccion extends Model
{
    use HasFactory;

    protected $table = 'procesos_seleccion';

    protected $fillable = [
        'filter',
        'year',
        'number',
        'date',
        'date_label',
        'title',
        'description',
        'file',
        'file_type',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'year' => 'integer',
    ];

    // Auto-generación por si se crea desde seeders o scripts
    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->year && $model->number) {
                $model->number = str_pad(ltrim($model->number, '0'), 3, '0', STR_PAD_LEFT);
                $model->filter = "{$model->year}-{$model->number}";
            }

            if ($model->date && empty($model->date_label)) {
                $date = Carbon::parse($model->date)->locale('es');
                $model->date_label = 'Publicado: ' . $date->translatedFormat('d \d\e F - Y');
            }

            if ($model->file) {
                $model->file_type = strtolower(pathinfo($model->file, PATHINFO_EXTENSION)) ?: 'pdf';
            }
        });
    }
}