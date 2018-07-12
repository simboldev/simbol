<?php

namespace App;

use App\recomendacion;
use Illuminate\Database\Eloquent\Model;

class amigo extends Model
{
    //
    // protected $primaryKey = 'idamigos';
    protected $fillable = [
    	'user1',
    	'user2',
    ];  

    public function recomendacion()
    {
    	return $this->hasMany(recomendacion::class);
    }
}
