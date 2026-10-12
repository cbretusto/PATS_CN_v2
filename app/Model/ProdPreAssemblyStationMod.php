<?php

namespace App\Model;

use App\Model\ModeOfDefect;
use Illuminate\Database\Eloquent\Model;

class ProdPreAssemblyStationMod extends Model
{
    protected $table = "prod_pre_assembly_station_mods";
    protected $connection = "mysql";

    public function modDetails(){
        return $this->hasOne(ModeOfDefect::class, 'id', 'mod_id');
    }
}
