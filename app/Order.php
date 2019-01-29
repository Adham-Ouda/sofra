<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model 
{

    protected $table = 'orders';
    public $timestamps = true;
    protected $fillable = array('total', 'notes', 'address', 'payment_method_id', 'delivery_fee', 'commission', 'order_price', 'status',
        'restaurant_id','client_id', 'net');

    /* protected $appends = ['net_price'];

    public function getNetPriceAttribute()
    {
        return $this->total - $this->commission;
    } */

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }

    public function products()
    {
        return $this->belongsToMany('App\Product')->withPivot('quantity', 'price', 'special_order');
    }

    public function notifications()
    {
        return $this->hasMany('App\Notification');
    }

    public function paymentMethod(){
    
    $this->hasOne('App\PaymentMethod');
    
    }

}