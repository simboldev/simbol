<?php

namespace App;

use App\posturasMatches;
use Illuminate\Database\Eloquent\Model;

class tracking extends Model
{
    //
    // protected $primaryKey = 'idtracking';
    protected $fillable = [
    	'transferi',
    	'metransfirieron',
    	'conformetransfiere',
    	'conformetransferido',
        'id',
        'iduser',
        'iduser2',
        'opsatisf1',
        'opsatisf2'
    ]; 

    public function posturasMatches()
    {
    	return $this->hasOne(posturasMatches::class);
    }
}
