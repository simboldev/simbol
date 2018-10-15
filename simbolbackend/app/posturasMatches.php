<?php

namespace App;

use App\User;
use App\chat;
use App\postura;
use App\denuncia;
use App\tracking;
use App\estatusOperacion;
use App\confiabilidad;
use App\recomendacion;
use Illuminate\Database\Eloquent\Model;
use App\posturasMatch_has_calificaciones;


class posturasMatches extends Model
{
    //
    protected $primaryKey = 'idposturasMatch';
    protected $fillable = [
    	'posturas_idposturas',
    	'estatusMatch',
    	'users_idusers',
    	'iduser2',
    	'cronometro',
    	'confiabilidad_idconfiabilidad',
    	'denuncias_iddenuncias',
    	'tracking_idtracking',
        'estatusOperaciones_idestatusOperacion',
        'postura_contraparte_id',
        'acepta_user_propietario',
        'acepta_user_contraparte'
    ]; 

    public function User()
    {
    	return $this->belongsToMany(User::class);
    }

    public function postura()
    {
    	return $this->belongsToMany(postura::class);
    }

    public function denuncia()
    {
    	return $this->belongsTo(denuncia::class);
    }

    public function confiabilidad()
    {
    	return $this->belongsTo(confiabilidad::class);
    }

    public function chat()
    {
    	return $this->hasMany(chat::class);
    }

    public function tracking()
    {
    	return $this->belongsTo(tracking::class);
    }

    public function recomendacion()
    {
    	return $this->hasMany(recomendacion::class);
    }

    public function posturasMatch_has_calificaciones()
    {
        return $this->belongsToMany(posturasMatch_has_calificaciones::class);
    }

    public function statusOperacion()
    {
        return $this->hasOne(statusOperacion::class);
    }

    public function negociacion()
    {
        return $this->belongsToMany(negociacion::class);
    }
    
}
