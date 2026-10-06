<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Directiva extends Model
{
    protected $fillable = ['titulo', 'descripcion', 'fecha', 'pdf', 'status'];
}
