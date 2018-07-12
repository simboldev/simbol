<?php

namespace App;

use App\User;
use App\bancos_pais_monedas;
use App\posturas_has_users_bancos;
use Illuminate\Database\Eloquent\Model;

class users_bancos_pais_monedas extends Model
{
    //
    // protected $primaryKey = 'idusers_bancos_pais_monedas';
    protected $fillable = [
    	'iduser',
    	'idbancos_pais_monedas'
    ];

    public function User()
	{
		return $this->belongsToMany(User::class);
	}

	 public function bancos_pais_monedas()
	{
		return $this->belongsToMany(bancos_pais_monedas::class);
	}

     public function posturas_has_users_bancos()
    {
        return $this->belongsToMany(posturas_has_users_bancos::class);
    }
}
