<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class PackingOperator extends Model
{
    //
    protected $table = 'packing_operators';
    protected $connection = 'mysql';

    	public function user_details()
    	{
	    	return $this->hasOne(User::class, 'id', 'emp_id');
		}

		public function lotapp_details()
    	{
	    	return $this->hasMany(oqcLotApp::class, 'lot_batch_no', 'batch');
		}

        public function oqcvir_details()
        {
            return $this->hasMany(oqcVIR::class, 'fkid_oqclotapp', 'lotapp_fkid');
        }
}
