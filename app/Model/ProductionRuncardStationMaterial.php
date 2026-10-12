<?php

namespace App\Model;

use App\Model\ProductionRuncardStation;
use Illuminate\Database\Eloquent\Model;

class ProductionRuncardStationMaterial extends Model
{
    //
    protected $table = 'prod_runcard_station_materials';
    protected $connection = 'mysql';

    public function runcard_station(){
    	return $this->hasOne(ProductionRuncardStation::class, 'id', 'production_runcard_station_id');
    }
}
