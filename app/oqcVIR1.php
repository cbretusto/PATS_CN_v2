<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\oqcLotApp;
// use App\oqcVIR;

class oqcVIR1 extends Model
{
    protected $table = 'oqcvir';
    protected $connection = 'mysql';

	public function oqclotapp_details(){
	    return $this->hasOne(oqcLotApp::class, 'id', 'fkid_oqclotapp')->orderBy('reel_lot','ASC');
	}


	public function user_details(){
	    return $this->hasOne(User::class, 'id', 'insp_name');
	}

}
