<?php 

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class PackingInspector extends Model
{
	protected $table = 'packing_inspectors';
	protected $connection  = 'mysql';

	public function user_details()
    	{
	    	return $this->hasOne(User::class, 'id', 'emp_id');
		}

	public function user_details_oqc()
    	{
	    	return $this->hasOne(User::class, 'id', 'oqc_inspector');
		}

	public function batch_details()
    	{
	    	return $this->hasOne(oqcLotApp::class, 'lot_batch_no', 'batch');
		}
}