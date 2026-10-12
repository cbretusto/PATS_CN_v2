<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\EEDMSTrouble;

class EEDMSGeneralLogistics extends Model
{
    //
    protected $table = 'generallogistics';
    protected $connection = 'mysql_rapid_eedms_v2';

    public function trouble_info(){
        return $this->hasOne(EEDMSTrouble::class, 'fkid_generallogistics', 'id');
    }
}
