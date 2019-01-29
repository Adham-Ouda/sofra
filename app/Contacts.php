<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    protected $table = 'contacts';
    public $timestamps = true;
    protected $fillable = array('name', 'email', 'type', 'message', 'mobile');

}
