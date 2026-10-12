<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Station;
use App\Model\SubStation;
use App\Model\MaterialProcess;

class StationSubStation extends Model
{
    //
	protected $table = 'station_sub_stations';
    protected $connection = 'mysql';

    public function station(){
    	return $this->hasOne(Station::class, 'id', 'station_id');
    }

    public function sub_station(){
    	return $this->hasOne(SubStation::class, 'id', 'sub_station_id');
    }

    public function material_process(){
    	return $this->hasMany(MaterialProcess::class, 'station_sub_station_id', 'id');
    }
}
