<?php

namespace App;

use App\posturasMatches;
use Illuminate\Database\Eloquent\Model;

class denuncia extends Model
{
    //
    // protected $primaryKey = 'iddenuncias';
    protected $fillable = [
    	'idvictima',
    	'idvictimario',
    	'nocumpletiempotransf',
        'transfmontodif',
        'transfnorecibida',
        'nocumplecondpreest',
    	'detalle',
    	'evidencias',
    	'fecha',
    	'estatusdenuncia',
    ]; 

    public function posturasMatches()
    {
        return $this->hasOne(posturasMatches::class);
    }
    
}
