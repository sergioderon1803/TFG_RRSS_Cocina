<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model {
    protected $table = 'recetas';
    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'tipo',
        'ingredientes',
        'procedimiento',
        'autor',
        'imagen',
    ];
}
