<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CnPptsDevice extends Model
{
    protected $table = 'devices';
    protected $connection = 'mysql_cn_ppts_v2_1';
}
