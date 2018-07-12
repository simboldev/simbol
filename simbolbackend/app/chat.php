<?php

namespace App;

use App\posturasMatches;
use Illuminate\Database\Eloquent\Model;

class chat extends Model
{
    //
    // protected $primaryKey = 'idchat';
    protected $fillable = [
        'idchat',
        'iduser',
        'username',
        'textouser',
        'adjuntouser',
        'posturasMatches_idposturasMatch'
    ]; 

    public function postrurasMatches()
    {
         return $this->hasOne(posturasMatches::class);
    }
}
