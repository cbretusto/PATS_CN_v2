<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\ProductionRuncard;
use App\Model\PartsPrep;
use App\Model\ProdRuncardMaterialList;

class WBSKitIssuance extends Model
{
    //
    protected $table = 'tbl_wbs_kit_issuance';
    protected $connection = 'mysql_subsystem';

    public function kit_issuance(){
        return $this->hasOne(MaterialIssuanceSubSystem::class, 'issuance_no', 'issue_no');
    }

    public function prod_runcards(){
        return $this->hasOne(ProductionRuncard::class, 'wbs_kit_issuance_id', 'id');
    }

    public function parts_prep(){
        return $this->hasOne(PartsPrep::class, 'wbs_kit_issuance_id', 'id');
    }

    public function parts_prep_info(){
        return $this->hasOne(PartsPrep::class, 'wbs_kit_issuance_id', 'id');
    }

    public function prod_runcard_material_list(){
        return $this->hasMany(ProdRuncardMaterialList::class, 'issuance_id', 'id');
    }

    public function pats_kitting_info(){
        return $this->hasOne(Kitting::class, 'kit_issuance_id', 'id');
    }
}
