<?php

namespace App;

use App\notificaciones_has_users;
use Illuminate\Database\Eloquent\Model;

class notificacion extends Model
{
    //
    // protected $primaryKey = 'idnotificaciones';
    protected $fillable = [
    	'titulo',
    	'cuerpo',
    	'adjunto',
        'notLeida',
        'status_notifications_id',
    ]; 

   
    public function notificaciones_has_users()
    {
        return $this->belongsToMany(notificaciones_has_users::class);
    }


}
