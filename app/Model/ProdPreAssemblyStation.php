<?php

namespace App\Model;

use App\Model\Device;
use App\Model\Station;
use App\Model\SubStation;
use App\Model\ProdPreAssembly;
use Illuminate\Database\Eloquent\Model;
use App\Model\ProdPreAssemblyStationMod;
use App\Model\ProdPreAssemblyStationMachine;
use App\Model\ProdPreAssemblyStationMaterial;
use App\Model\ProdPreAssemblyStationOperator;

class ProdPreAssemblyStation extends Model
{
    protected $table = "prod_pre_assembly_stations";
    protected $connection = "mysql";
    
    public function materialProcessDetails(){
        return $this->hasOne(Device::class, 'id', 'mat_proc_id');
    }
    public function stationDetails(){
        return $this->hasOne(Station::class, 'id', 'station_id');
    }
    public function subStationDetails(){
        return $this->hasOne(SubStation::class, 'id', 'sub_station_id');
    }
    public function materialDetails(){
        return $this->hasMany(ProdPreAssemblyStationMaterial::class, 'pre_assembly_station_id', 'id');
    }
    public function operators(){
        return $this->hasMany(ProdPreAssemblyStationOperator::class, 'pre_assembly_station_id', 'id');
    }
    public function machines(){
        return $this->hasMany(ProdPreAssemblyStationMachine::class, 'pre_assembly_station_id', 'id');
    }
    public function MOD(){
        return $this->hasMany(ProdPreAssemblyStationMod::class, 'pre_assembly_station_id', 'id');
    }

    public function preAssemblyDetails(){
        return $this->hasOne(ProdPreAssembly::class, 'id', 'pre_assembly_id');
    }
    
}