<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\SubStation;

class Station extends Model
{
    //
    protected $table = 'stations';
    protected $connection = 'mysql';

    // public function sub_stations(){
    // 	return $this->hasMany(SubStation::class, 'station_id', 'id');
    // }
}
