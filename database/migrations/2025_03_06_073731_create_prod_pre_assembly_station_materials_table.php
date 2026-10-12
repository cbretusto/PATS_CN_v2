<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdPreAssemblyStationMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_pre_assembly_station_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pre_assembly_id');
            $table->unsignedBigInteger('pre_assembly_station_id');
            $table->string('item_code');
            $table->string('item_name');
            $table->string('item_lot_no');
            $table->string('tbl_wbs')->comment('1-tbl_wbs_material_kitting_details, 2-tbl_wbs_sakidashi_issuance_item');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prod_pre_assembly_station_materials');
    }
}
