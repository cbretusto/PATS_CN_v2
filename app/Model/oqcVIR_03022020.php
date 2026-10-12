<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class oqcVIR extends Model
{
    protected $table = 'oqcvir';
    protected $connection = 'mysql';

	public function oqclotapp_details(){
	    return $this->hasOne(oqcVIR::class, 'id', 'fkid_oqclotapp');
	}

	public function user_details(){
	    return $this->hasOne(User::class, 'id', 'insp_name');
	}

}
