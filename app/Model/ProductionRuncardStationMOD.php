<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\ProductionRuncard;
use App\Model\ModeOfDefect;

class ProductionRuncardStationMOD extends Model
{
    //
	protected $table = 'prod_runcard_station_mods';
    protected $connection = 'mysql';

    public function production_runcard_details(){
    	return $this->hasOne(ProductionRuncard::class, 'id', 'production_runcard_id');
    }

    public function mod_details(){
    	return $this->hasOne(ModeOfDefect::class, 'id', 'mod_id');
    }
}
