<?php

namespace App;

use App\users_has_pais;
use App\banco_pais_monedas;
use Illuminate\Database\Eloquent\Model;

class pais extends Model
{
    //
    // protected $primaryKey = 'idpais';
    protected $fillable = [
    	'nombre',
    	'nacionalidad',
    ]; 

	public function users_has_pais()
	{
		return $this->belongsToMany(users_has_pais::class);
	}

	public function banco_pais_monedas()
	{
		return $this->belongsToMany(banco_pais_monedas::class);
	}
}
