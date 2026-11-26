<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\oqcVIR;
use App\Model\ProdPrelimInspection;
use App\User;

class OQCPrelimInspection extends Model
{
	protected $table = 'oqc_prelim_inspections';
	protected $connection = "mysql";

    public function prod_prelim_details()
    {
        return $this->hasMany(ProdPrelimInspection::class, 'pack_code_no', 'pack_code_no')->orderBy('operator_judgement','ASC');
    }

    public function oqcvir_details()
    {
        return $this->hasMany(oqcVIR::class, 'packing_code', 'pack_code_no');
    }

    public function user_details()
    {
        return $this->hasOne(User::class, 'id', 'emp_id');
    }
}