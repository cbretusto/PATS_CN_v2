<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OQCDBM extends Model
{
    protected $table = 'hrisdb';
    protected $connection = 'mssql_oqcdbm';
}
