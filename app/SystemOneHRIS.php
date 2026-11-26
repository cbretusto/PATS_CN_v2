<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemOneHRIS extends Model
{
    //
    protected $table = "tbl_EmployeeInfo";
    protected $connection = "mysql_systemone";
}
