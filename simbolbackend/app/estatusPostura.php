<?php

namespace App;

use App\postura;
use Illuminate\Database\Eloquent\Model;

class estatusPostura extends Model
{
    //
    // protected $primaryKey = 'idestatusPosturas';
    protected $fillable = [
    	'nombreEstatus',
    ]; 

    public function postura()
    {
    	return $this->hasMany(postura::class);
    }
}
