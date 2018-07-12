<?php

namespace App;

use App\User;
use App\pais;
use Illuminate\Database\Eloquent\Model;

class users_has_pais extends Model
{
    //
    // protected $primaryKey = 'users_has_pais';
    protected $fillable = [
    	'users_id',
    	'pais_idpais',
    ];	


    public function User()
	{
		return $this->belongsToMany(User::class);
	}

	 public function pais()
	{
		return $this->belongsToMany(pais::class);
	}
}
