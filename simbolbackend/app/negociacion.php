<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class negociacion extends Model
{
    //
    protected $fillable = [
    	'idbanco',
        'aba',
    	'nrocuenta',
    	'email',
    	'nroidentificacion',
    	'comprobante',
    	'idposturamatch',
        'estatusnegociacion',
        'iduser'
    ];

    public function banco()
    {
    	return $this->hasMany(chat::class);
    }

    public function postrurasMatches()
    {
         return $this->hasMany(posturasMatches::class);
    }
    
    public function user()
    {
        return $this->hasMany(chat::class);
        
    }
}
