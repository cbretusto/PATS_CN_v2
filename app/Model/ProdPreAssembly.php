<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProdPreAssembly extends Model
{
    protected $table = "prod_pre_assemblies";
    protected $connection = "mysql";

    protected $fillable = [
        'po_no',
        'lot_qty',
        'pre_assy_no',
        'pre_assy_station_code',
        'other_docs_number',
        'revision',
        'remarks',
        'created_by'
    ];

    public function stationDetails(){
        return $this->hasMany(ProdPreAssemblyStation::class, 'pre_assembly_id', 'id');
    }
}
