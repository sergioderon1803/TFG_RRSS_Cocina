<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'user_type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relaciones
    public function recetas()
    {
        return $this->hasMany(Receta::class, 'autor_receta', 'id');
    }
    
    public function perfil()
    {
        return $this->hasOne(Perfil::class, 'id_user');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'id_user');
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'id_user');
    }

    public function recetasGuardadas()
    {
        return $this->belongsToMany(Receta::class, 'guardar_receta', 'id_user', 'id_receta')
                    ->withPivot('f_guardar');
    }

    public function recetasGustadas()
    {
        return $this->belongsToMany(Receta::class, 'gustar_receta', 'id_user', 'id_receta')
                    ->withPivot('f_gustar');
    }

    public function seguidores()
    {
        return $this->belongsToMany(User::class, 'seguir_usuario', 'id_user', 'id_seguidor')
                    ->withPivot('f_seguimiento');
    }

    public function seguidos()
    {
        return $this->belongsToMany(User::class, 'seguir_usuario', 'id_seguidor', 'id_user')
                    ->withPivot('f_seguimiento');
    }
}
