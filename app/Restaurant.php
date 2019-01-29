<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model 
{

    protected $table = 'restaurants';
    protected $hidden = ['password','api_token'];
    public $timestamps = true;
    protected $fillable = array('name', 'email', 'minium', 'mobile', 'whatsapp', 'delivery_fee','password', 'image', 'status', 
        'quarter', 'rate','city_id');

    public function notifications()
    {
        return $this->morphMany('App\Notification', 'notificationable');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function offers()
    {
        return $this->hasMany('App\Offer');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    public function tokens()
    {
        return $this->morphMany('App\Token', 'tokenable');
    }

}