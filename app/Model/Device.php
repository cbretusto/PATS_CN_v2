<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\MaterialProcess;
use App\PPTSDevice;

class Device extends Model
{
    //
    protected $table = 'devices';
    protected $connection = 'mysql';

    public function material_process(){
    	return $this->hasMany(MaterialProcess::class, 'device_id', 'id');
    }

    public function ppts_device_details(){
        return $this->hasOne(PPTSDevice::class, 'barcode', 'barcode');
    }
}
