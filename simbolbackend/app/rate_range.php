<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rate_range extends Model
{
  protected $fillable = [
    'initial_amount',
    'final_amount'
  ];
}
