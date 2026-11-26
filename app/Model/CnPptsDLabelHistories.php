<?php

namespace App\Model;

use App\User;

use App\Model\CnPptsDLabel;
use Illuminate\Database\Eloquent\Model;

class CnPptsDLabelHistories extends Model
{
    protected $table = 'd_label_histories';
    protected $connection = 'mysql_cn_ppts_v2_1';

    public function cn_ppts_d_label_info(){
        return $this->hasOne(CnPptsDLabel::class, 'id', 'd_label_id');
    }

    public function cn_ppts_user_info(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
