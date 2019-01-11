<?php

namespace App;

use App\negociacion;
use Illuminate\Database\Eloquent\Model;

class estatusNegociacion extends Model
{
    //
    protected $fillable = [
    	'estatus',
    ];

    public function negociacion()
    {
    	return $this->hasMany(negociacion::class);
    }
}
