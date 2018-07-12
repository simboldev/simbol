<?php

namespace App;

use App\posturasMatches;
use App\calificaciones;
use Illuminate\Database\Eloquent\Model;

class posturasMatch_has_calificaciones extends Model
{
    //
    protected $fillable = [
    	'idposturasMatch',
    	'idcalificaciones',
    ];

     public function posturasMatches()
	{
		return $this->belongsToMany(posturasMatches::class);
	}

	 public function calificaciones()
	{
		return $this->belongsToMany(calificaciones::class);
	}
}
