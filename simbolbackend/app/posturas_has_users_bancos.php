<?php

namespace App;

use App\postura;
use App\users_bancos_pais_monedas;
use Illuminate\Database\Eloquent\Model;

class posturas_has_users_bancos extends Model
{
    //
    // protected $primaryKey = 'idposturas_has_users_bancos';
    protected $fillable = [
    	'posturas_id',
    	'users_bancos_pais_monedas_id'
    ];

    public function postura()
	{
		return $this->belongsToMany(postura::class);
	}

	 public function users_bancos_pais_monedas()
	{
		return $this->belongsToMany(users_bancos_pais_monedas::class);
	}
}
