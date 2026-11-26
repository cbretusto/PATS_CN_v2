<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


class RapidDLabel extends Model
{
    //
    protected $table = 'd_label';
    protected $connection = 'mysql_rapid_dlabel';

	// public function wbs_kitting(){
 //        return $this->hasOne(MaterialIssuanceSubSystem::class, 'device_name', 'doc_title');
 //    }
}
