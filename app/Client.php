<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model 
{

    protected $table = 'clients';
    protected $hidden = ['password','api_token'];
    public $timestamps = true;
    protected $fillable = array('name', 'email', 'home_description', 'mobile', 'quarter','city_id','password');

    public function notifications()
    {
        return $this->morphMany('App\Notification', 'notificationable');
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function tokens()
    {
        return $this->morphMany('App\Token', 'tokenable');
    }

}