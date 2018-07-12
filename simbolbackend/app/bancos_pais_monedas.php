<?php

namespace App;

use App\banco;
use App\pais;
use App\moneda;
use App\users_bancos_pais_monedas;
use Illuminate\Database\Eloquent\Model;

class bancos_pais_monedas extends Model
{
    //
    // protected $primaryKey = 'idbancos_pais_monedas';
    protected $fillable = [
    	'idbanco',
    	'idpais',
    	'idmoneda'
    ];

	 public function banco()
	{
		return $this->belongsToMany(banco::class);
	}

	 public function pais()
	{
		return $this->belongsToMany(pais::class);
	}

     public function moneda()
	{
		return $this->belongsToMany(moneda::class);
	}

     public function users_bancos_pais_monedas()
    {
        return $this->belongsToMany(bancos_pais_monedas::class);
    }
}
