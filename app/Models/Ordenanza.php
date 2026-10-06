<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordenanza extends Model
{
protected $fillable = ['numero', 'anio', 'fecha', 'pdf', 'status'];
}
