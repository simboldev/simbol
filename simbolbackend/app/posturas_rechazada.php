<?php

namespace App;

use App\postura;
use Illuminate\Database\Eloquent\Model;


class posturas_rechazada extends Model
{
    //
    protected $fillable = [
        'mi_postura_id',
        'postura_rechazada_id'
    ];

    public function postura()
    {
        return $this->belongsToMany(postura::class);
    }
}