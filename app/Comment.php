<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model 
{

    protected $table = 'comments';
    public $timestamps = true;
    protected $fillable = array('rate', 'body', 'name', 'created_at', 'restaurant_id','client_id');

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }

}