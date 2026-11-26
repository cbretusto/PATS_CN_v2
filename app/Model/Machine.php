<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\EEDMSGeneralLogistics;

class Machine extends Model
{
    //
    protected $table = 'machines';
    protected $connection = 'mysql';

    public function eedms_machine_info(){
    	return $this->hasOne(EEDMSGeneralLogistics::class, 'machine_code_number', 'barcode');
    }
}
