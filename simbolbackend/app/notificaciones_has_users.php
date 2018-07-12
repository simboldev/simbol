<?php

namespace App;

use App\User;
use App\notificacion;
use Illuminate\Database\Eloquent\Model;

class notificaciones_has_users extends Model
{
    //
    // protected $primaryKey = 'idnotificaciones_has_users';
     protected $fillable = [
    	'notificaciones_idnotificaciones',
    	'users_idusers',
    	'postura_match_id',
    ];

    public function User()
	{
		return $this->belongsToMany(User::class);
	}

	 public function notificacion()
	{
		return $this->belongsToMany(notificacion::class);
	}

    public function posturasMatches()
    {
        return $this->hasMany(posturasMatches::class);
    }
}
