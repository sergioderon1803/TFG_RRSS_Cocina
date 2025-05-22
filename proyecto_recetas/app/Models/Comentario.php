<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comentario extends Model
{
    use HasFactory;

    protected $table = 'comentarios';
    public $timestamps = false;

    protected $fillable = ['id_receta', 'id_user', 'contenido', 'f_creacion'];

    public function receta()
    {
        return $this->belongsTo(Receta::class, 'id_receta');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'id_comentario');
    }
}
