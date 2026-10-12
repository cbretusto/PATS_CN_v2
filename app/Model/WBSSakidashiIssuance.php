<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\ProductionRuncard;
use App\Model\PartsPrep;
use App\Model\Device;
use App\Model\MaterialIssuance;
use App\Model\ProdRuncardMaterialList;

class WBSSakidashiIssuance extends Model
{
    //
    protected $table = 'tbl_wbs_sakidashi_issuance';
    protected $connection = 'mysql_subsystem';

    public function tbl_wbs_sakidashi_issuance_item(){
        return $this->hasOne(WBSSakidashiIssuanceItem::class, 'issuance_no', 'issuance_no');
    }

    public function device_info(){
        return $this->hasOne(Device::class, 'barcode', 'device_code');
    }

    public function prod_runcards(){
        return $this->hasOne(ProductionRuncard::class, 'wbs_sakidashi_issuance_id', 'id');
    }

    public function parts_prep_info(){
        return $this->hasOne(PartsPrep::class, 'wbs_kit_issuance_id', 'id');
    }

    public function material_issuance_info(){
        return $this->hasOne(MaterialIssuance::class, 'id', 'tbl_wbs_material_kitting_id');
    }

    public function tbl_wbs_sakidashi_issuance_many_item(){
        return $this->hasOne(WBSSakidashiIssuanceItem::class, 'issuance_no', 'issuance_no');
    }

    public function prod_runcard_material_list(){
        return $this->hasMany(ProdRuncardMaterialList::class, 'issuance_id', 'id');
    }
}
