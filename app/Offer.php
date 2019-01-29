<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model 
{

    protected $table = 'offers';
    public $timestamps = true;
    protected $fillable = array('name', 'price', 'description', 'image', 'duration');

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }

}