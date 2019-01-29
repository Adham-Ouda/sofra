<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model 
{

    protected $table = 'payments';
    public $timestamps = true;
    protected $fillable = array('payment', 'restaurant_id', 'payment_method_id');

    public function restaurant()
    {
        return $this->belongsTo('App\Restaurant');
    }

    public function paymentMethod()
    {
        return $this->belongsTo('App\PaymentMethod');
    }

}