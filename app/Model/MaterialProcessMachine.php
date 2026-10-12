<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Machine;

class MaterialProcessMachine extends Model
{
    //
    protected $table = 'material_process_machines';
    protected $connection = 'mysql';

    public function machine_info(){
        return $this->hasOne(Machine::class, 'id', 'machine_id');
    }
}
