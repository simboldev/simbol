<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class tipousuario extends Model
{
    //
	// protected $primaryKey = 'idtipousuario';
    protected $fillable = [
    	'tipo',
    	'perfil'
    ]; 

    public function User()
    {
    	return $this->hasMany(User::class);
    }

}
