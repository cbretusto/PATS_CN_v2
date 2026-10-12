<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ProductionRuncardStationOperator extends Model
{
    //
    protected $table = 'prod_runcard_station_operators';
    protected $connection = 'mysql';

	public function operator_info(){
        return $this->hasOne(User::class, 'id', 'operator_id');
    }
}
