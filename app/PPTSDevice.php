<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PPTSDevice extends Model
{
    //

    protected $table = "devices";
    protected $connection = "mysql_cn_ppts_v2_1";
}
