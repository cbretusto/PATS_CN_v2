<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class PackingOperator2 extends Model
{
    //
    protected $table = 'packing_operators2';
    protected $connection = 'mysql';

    	public function user_details()
    	{
	    	return $this->hasOne(User::class, 'id', 'emp_id');
		}
}
