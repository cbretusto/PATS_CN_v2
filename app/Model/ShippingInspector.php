<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\PackingOperator;
use App\Model\PackingInspector;
use App\Model\ShippingOperator;

class ShippingInspector extends Model
{
    protected $table = 'shipping_inspectors';
    protected $connection = 'mysql';

    public function user_details()
    {
	   	return $this->hasOne(User::class, 'id', 'emp_id');
	}

	public function packop_details()
	{
		return $this->hasMany(PackingOperator::class, 'pack_code_no', 'pack_code_no');
	}

	public function packin_details()
	{
		return $this->hasOne(PackingInspector::class, 'pack_code_no', 'pack_code_no');
	}

	public function shipop_details()
	{
		return $this->hasOne(ShippingOperator::class, 'pack_code_no', 'pack_code_no');
	}
}
