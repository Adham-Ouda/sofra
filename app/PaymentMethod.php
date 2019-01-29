<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'payment_methods';
    public $timestamps = true;
    protected $fillable = array('name');

    public function order(){
	
	$this->belongsTo('App\Order');
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }
    
}
