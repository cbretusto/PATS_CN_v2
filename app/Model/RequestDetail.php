<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RequestDetail extends Model
{
    protected $table = 'tbl_request_detail';
    protected $connection = 'mysql_subsystem';
}
