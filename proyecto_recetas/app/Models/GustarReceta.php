<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GustarReceta extends Pivot
{
    protected $table = 'gustar_receta';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id_receta', 'id_user', 'f_gustar'];

    public function receta()
    {
        return $this->belongsTo(Receta::class, 'id_receta');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
