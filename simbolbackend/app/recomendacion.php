<?php

namespace App;

use App\amigo;
use App\posturasMatches;
use Illuminate\Database\Eloquent\Model;

class recomendacion extends Model
{
    //
    // protected $primaryKey = 'idrecomendaciones';
    protected $fillable = [
    	'cuerpomensaje',
    	'fecha',
    	'idrecomienda',
    	'idrecomendado',
    	'posturasMatch_idposturasMatch',
    	'amigos_idamigos'
    ]; 

    public function posturasMatches()
    {
    	return $this->belongsTo(posturasMatches::class);
    }

    public function amigo()
    {
    	return $this->belongsTo(amigo::class);
    }
}
