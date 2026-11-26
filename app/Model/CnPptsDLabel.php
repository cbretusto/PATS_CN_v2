<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Model\CnPptsDLabelHistories;

class CnPptsDLabel extends Model
{
    protected $table = 'd_labels';
    protected $connection = 'mysql_cn_ppts_v2_1';

    public function cn_ppts_d_label_history_details(){
        return $this->hasMany(CnPptsDLabelHistories::class, 'd_label_id', 'id');
    }

    public function cn_ppts_device_info(){
        return $this->hasOne(CnPptsDevice::class, 'name', 'prodname');
    }

}
