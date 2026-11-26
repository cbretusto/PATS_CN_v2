<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\ProductionRuncard;
use App\Model\MaterialIssuanceSubSystem;
use App\Model\WBSKitIssuance;
use App\Model\ProductionRuncardStationMaterial;
use App\Model\WBSSakidashiIssuance;

class ProdRuncardMaterialList extends Model
{
    //
    protected $table = 'prod_runcard_material_lists';
    protected $connection = 'mysql';

    public function prod_runcard_details(){
    	return $this->hasOne(ProductionRuncard::class, 'id', 'prod_runcard_id');
    }

    public function wbs_material_kitting(){
    	return $this->hasOne(WBSKitIssuance::class, 'id', 'issuance_id');
    }

    public function prod_runcard_station_material_info(){
    	return $this->hasOne(ProductionRuncardStationMaterial::class, 'issuance_id', 'issuance_id');
    }

    public function wbs_sakidashi_issuance(){
        return $this->hasOne(WBSSakidashiIssuance::class, 'id', 'issuance_id');
    }



    
}
