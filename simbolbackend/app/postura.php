<?php

namespace App;

use App\posturasMatches;
use App\banco_has_posturas;
use App\posturas_has_users_bancos;
use App\estatuspostura;
use Illuminate\Database\Eloquent\Model;

class postura extends Model
{
    //
    protected $fillable = [
        'quiero_moneda_id',
        'tengo_moneda_id',
        'tengo',
        'tasacambio',
        'fechadesde',
        'fechahasta',
        'comentarios',
        'iduser',
        'estatusPosturas_idestatusPosturas',
        'fraccionar',
        'quiero'
    ]; 

    public function posturasMatches()
    {
        return $this->hasMany(posturasMatches::class);
    }

    public function banco_has_posturas()
    {
        return $this->belongsToMany(banco_has_posturas::class);
    }

     public function posturas_has_users_bancos()
    {
        return $this->belongsToMany(posturas_has_users_bancos::class);
    }

     public function estatuspostura()
    {
        return $this->belongsToMany(estatuspostura::class);
    }
}
