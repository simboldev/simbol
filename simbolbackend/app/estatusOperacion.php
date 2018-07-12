<?php

namespace App;

use App\posturasMatches;
use Illuminate\Database\Eloquent\Model;

class estatusOperacion extends Model
{
    //
	// protected $primaryKey = 'idestatusOperacion';
	protected $fillable = [
    	'estatus',
    	'descripcion',
    ]; 

    public function posturasMatches()
    {
    	return $this->hasMany(posturasMatches::class);
    }
}
