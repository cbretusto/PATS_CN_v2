<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\oqcLotApp;
use App\Model\oqcVIR;
use App\Model\MaterialIssuanceSubSystem;
use App\Model\ProductionRuncardStation;
use App\Model\ProdRuncardMaterialList;
use App\Model\MaterialProcess;
use App\Model\MaterialIssuanceDetails;

class ProductionRuncard extends Model
{
    //

    protected $table = 'production_runcards';
    protected $connection = 'mysql';

    public function supervisor_prod_info(){
        return $this->hasOne(User::class, 'id', 'supervisor_prod');
    }

    public function supervisor_qc_info(){
        return $this->hasOne(User::class, 'id', 'supervisor_qc');
    }

    public function oqc_details(){
        return $this->hasOne(oqcLotApp::class, 'fkid_runcard', 'id'); //02072020
        // return $this->hasOne(oqcLotApp::class, 'lot_batch_no', 'runcard_no');
    }

    public function wbs_kitting(){
        return $this->hasOne(MaterialIssuanceSubSystem::class, 'po_no', 'po_no');
    }

    public function prod_runcard_material_list(){
        return $this->hasMany(ProdRuncardMaterialList::class, 'prod_runcard_id', 'id');
    }

    public function prod_runcard_station_details(){
        return $this->hasOne(ProductionRuncardStation::class, 'production_runcard_id', 'id');
    }

    public function prod_runcard_station_many_details(){
        return $this->hasMany(ProductionRuncardStation::class, 'production_runcard_id', 'id');
    }

    public function material_process_info(){
        return $this->hasOne(MaterialProcess::class, 'id', 'mat_proc_id');
    } 

    public function wbs_kitting_has_many(){
        return $this->hasMany(MaterialIssuanceSubSystem::class, 'po_no', 'po_no');
    }
}
