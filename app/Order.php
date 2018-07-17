<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user()
    {
    	// return $this->hasOne('App\User');
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function status()
    {
    	// return $this->hasOne('App\Status');
        return $this->hasOne('App\Status', 'id', 'status_id');
    }

}
