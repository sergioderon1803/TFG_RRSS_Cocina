<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeguirUsuario extends Pivot
{
    use HasFactory;
    
    protected $table = 'seguir_usuario';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id_user', 'id_seguidor', 'f_seguimiento'];

    public function seguido()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function seguidor()
    {
        return $this->belongsTo(User::class, 'id_seguidor');
    }
}
