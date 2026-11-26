<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProdPreAssemblyStationMachine extends Model
{
    protected $table = "prod_pre_assembly_station_machines";
    protected $connection = "mysql";

    public function machineDetails(){
        return $this->hasOne(Machine::class, 'id', 'machine_id');
    }
}
