<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GuardarReceta extends Pivot
{
    use HasFactory;

    protected $table = 'guardar_receta';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id_receta', 'id_user', 'f_guardar'];

    public function receta()
    {
        return $this->belongsTo(Receta::class, 'id_receta');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
