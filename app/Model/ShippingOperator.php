<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ShippingOperator extends Model
{
    //
    protected $table = 'shipping_operators';
    protected $connection = 'mysql';

    public function user_details()
    	{
	    	return $this->hasOne(User::class, 'id', 'emp_id');
		}
}
