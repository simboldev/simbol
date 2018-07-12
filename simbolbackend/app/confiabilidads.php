<?php

namespace App;

use App\posturasMatches;
use Illuminate\Database\Eloquent\Model;

class confiabilidad extends Model
{
    //
    // protected $primaryKey = 'idconfiabilidad';
    protected $fillable = [
    	'idusersolicitconfiab',
    	'iduserrecomconfiab',
    	'estatus',
    	'comentario',
    ]; 

    public function posturasMatches()
    {
    	return $this->hasOne(posturasMatches::class);
    }
}
