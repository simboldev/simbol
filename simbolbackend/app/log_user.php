<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class log_user extends Model
{
    //
    protected $fillable = [
    	'accion',
    	'user_id',
    	'posturas_match_id',
    ];	


    public function User()
	{
		return $this->belongsToMany(User::class);
	}

	public function posturasMatches()
    {
    	return $this->belongsToMany(posturasMatches::class);
    }
	
}
