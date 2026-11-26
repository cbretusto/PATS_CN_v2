<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Device;
// use App\Model\Station;
// use App\Model\SubStation;
use App\Model\StationSubStation;
use App\Model\Machine;
use App\Model\MaterialProcessManpower;
use App\Model\MaterialProcessMachine;
use App\Model\MaterialProcessMaterial;
use App\Model\ProductionRuncardStation;

class MaterialProcess extends Model
{
    //
    protected $table = 'material_processes';
    protected $connection = 'mysql';

    // public function station(){
    //  return $this->hasOne(Station::class, 'id', 'station_id');
    // }

    // public function sub_station(){
    //  return $this->hasOne(SubStation::class, 'id', 'sub_station_id');
    // }

    public function station_sub_station(){
        return $this->hasOne(StationSubStation::class, 'id', 'station_sub_station_id');
    }

    public function device(){
        return $this->hasOne(Device::class, 'id', 'device_id');
    }

    public function machine_info(){
        return $this->hasOne(Machine::class, 'id', 'machine_id');
    }

    public function manpowers_details(){
        return $this->hasMany(MaterialProcessManpower::class, 'mat_proc_id', 'id');
    }

    public function a_shift_manpowers_details(){
        return $this->hasMany(MaterialProcessManpower::class, 'mat_proc_id', 'id');
    }

    public function b_shift_manpowers_details(){
        return $this->hasMany(MaterialProcessManpower::class, 'mat_proc_id', 'id');
    }

    public function material_kitting_details(){
        return $this->hasMany(MaterialProcessMaterial::class, 'mat_proc_id', 'id');
    }

    public function sakidashi_details(){
        return $this->hasMany(MaterialProcessMaterial::class, 'mat_proc_id', 'id');
    }

    public function machine_details(){
        return $this->hasMany(MaterialProcessMachine::class, 'mat_proc_id', 'id');
    }

    public function material_details(){
        return $this->hasMany(MaterialProcessMaterial::class, 'mat_proc_id', 'id');
    }

    public function runcard_station_details(){
        return $this->hasMany(ProductionRuncardStation::class, 'mat_proc_id', 'id');
    }
}
