<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Station;
use App\Model\SubStation;
use App\Model\Machine;

class PartsPrepStation extends Model
{
    //
    protected $table = 'parts_prep_stations';
    protected $connection = 'mysql';

    public function station(){
    	return $this->hasOne(Station::class, 'id', 'station_id');
    }

    public function sub_station(){
    	return $this->hasOne(SubStation::class, 'id', 'sub_station_id');
    }
    public function machine(){
    	return $this->hasOne(Machine::class, 'id', 'machines_id');
    }
}
