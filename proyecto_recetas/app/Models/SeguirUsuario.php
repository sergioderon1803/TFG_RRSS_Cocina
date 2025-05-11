<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SeguirUsuario extends Pivot
{
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
