<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perfil extends Model
{
    use HasFactory;
    
    protected $table = 'perfil';
    protected $primaryKey = 'id_user';
    public $incrementing = false;

    protected $fillable = ['id_user', 'name', 'img_perfil', 'img_banner', 'biografia'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
