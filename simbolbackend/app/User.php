<?php

namespace App;

use App\tipousuario;
use App\posturasMatches;
use App\users_has_pais;
use App\notificaciones_has_users;
use App\users_bancos_pais_monedas;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function tipousuario()
    {
        return $this->belongsTo(tipousuario::class);
    }

    public function posturasMatches()
    {
        return $this->hasMany(posturasMatches::class);
    }

    public function users_has_pais()
    {
        return $this->belongsToMany(users_has_pais::class);
    }

    public function notificaciones_has_users()
    {
        return $this->belongsToMany(notificaciones_has_users::class);
    }

     public function users_bancos_pais_monedas()
    {
        return $this->belongsToMany(users_bancos_pais_monedas::class);
    }

    public function negociacion()
    {
        return $this->belongsToMany(negociacion::class);
    }

}
