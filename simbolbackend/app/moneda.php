<?php

namespace App;

use App\banco_pais_monedas;
use Illuminate\Database\Eloquent\Model;

class moneda extends Model
{
    //
    // protected $primaryKey = 'idmonedas';
    protected $fillable = [
    	'nombremoneda',
    	'admin_simbolo',
    ];

	public function banco_pais_monedas()
	{
		return $this->belongsToMany(banco_pais_monedas::class);
	}

}
