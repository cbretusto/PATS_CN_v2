<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\MaterialIssuanceSubSystem;


class RapidActiveDocs extends Model
{
    //
    protected $table = 'tbl_active_docs';
    protected $connection = 'mysql_rapid';

	public function wbs_kitting(){
        return $this->hasOne(MaterialIssuanceSubSystem::class, 'device_name', 'doc_title');
    }
}
