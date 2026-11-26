<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\RapidDLabel;


class RapidDLabelHistory extends Model
{
    protected $table = 'd_label_history';
    protected $connection = 'mysql_rapid_dlabel';

	public function d_label(){
        return $this->hasOne(RapidDLabel::class, 'id', 'd_label_id');
    }

}
