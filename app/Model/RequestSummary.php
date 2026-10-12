<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RequestSummary extends Model
{
    protected $table = 'tbl_request_summary';
    protected $connection = 'mysql_subsystem';
}
