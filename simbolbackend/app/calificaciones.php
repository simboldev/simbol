<?php

namespace App;


use App\posturasMatch_has_calificaciones;
use Illuminate\Database\Eloquent\Model;


class calificaciones extends Model
{
    //
    // protected $primaryKey = 'idcalificaciones';
    protected $fillable = [
    	'puntos',
    	'comentario',
        'iduser',
    	'idusuariocalificado',
        'idPosturasMatch',
    ]; 

     public function posturasMatch_has_calificaciones()
    {
        return $this->belongsToMany(posturasMatch_has_calificaciones::class);
    }

    public function User()
    {
        return $this->belongsToMany(User::class);
    }
}
