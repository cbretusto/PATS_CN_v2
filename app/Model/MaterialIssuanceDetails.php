<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MaterialIssuanceDetails extends Model
{
    //
    protected $table = 'tbl_wbs_material_kitting_details';
    protected $connection = 'mysql_subsystem';
}
