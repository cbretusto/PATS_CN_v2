<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ProdPreAssemblyStationOperator extends Model
{
    protected $table = "prod_pre_assembly_station_operators";
    protected $connection = "mysql";

    public function operatorDetails(){
        return $this->hasOne(User::class, 'id', 'operator_id');
    }
}
