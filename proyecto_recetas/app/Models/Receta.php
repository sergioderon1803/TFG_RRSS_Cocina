<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receta extends Model {
    use HasFactory;
    
    protected $table = 'recetas';
    public $timestamps = false;

    protected $fillable = [
        'titulo',
        'tipo',
        'ingredientes',
        'procedimiento',
        'autor_receta',
        'imagen',
        'estado',
        'created_at'
    ];

    // Relaciones
    public function autor()
    {
        return $this->belongsTo(User::class, 'autor_receta', 'id');
    }
    
    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'id_receta');
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'id_receta');
    }

    public function usuariosQueGuardaron()
    {
        return $this->belongsToMany(User::class, 'guardar_receta', 'id_receta', 'id_user')
                    ->withPivot('f_guardar');
    }

    public function usuariosQueGustaron()
    {
        return $this->belongsToMany(User::class, 'gustar_receta', 'id_receta', 'id_user')
                    ->withPivot('f_gustar');
    }

}
