<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model {
    public $timestamps = false; // Deshabilita campos predeterminados de Laravel 'created_at' y 'updated_at'
    protected $keyType = 'string'; // Indica que la clave primaria es un string
    public $incrementing = false; // Indica que la clave primaria no se autoincrementa (porque es un string en este caso)

    protected $table = 'usuarios';
    protected $primaryKey = 'email';
}
