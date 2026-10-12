<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\WBSKitIssuance;
use App\Model\ProdRuncardMaterialList;

use App\User;

class PartsPrep extends Model
{
    //
    protected $table = 'parts_preps';
    protected $connection = 'mysql';

    public function prod_runcard_material_list(){
    	return $this->hasMany(ProdRuncardMaterialList::class, 'issuance_id', 'wbs_kit_issuance_id');
    }

	public function user_details(){
	    return $this->hasOne(User::class, 'id', 'received_passed_by');
	}

}
