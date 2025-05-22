<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Respuesta extends Model
{
    use HasFactory;
    
    protected $table = 'respuestas';
    public $timestamps = false;

    protected $fillable = ['id_comentario', 'id_user', 'contenido', 'id_receta', 'id_user_respondido', 'f_creacion'];

    public function comentario()
    {
        return $this->belongsTo(Comentario::class, 'id_comentario');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function receta()
    {
        return $this->belongsTo(Receta::class, 'id_receta');
    }

    public function usuarioRespondido()
    {
        return $this->belongsTo(User::class, 'id_user_respondido');
    }
}
