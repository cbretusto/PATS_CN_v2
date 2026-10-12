<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdRuncardMaterialListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_runcard_material_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('prod_runcard_id');
            $table->unsignedBigInteger('issuance_id');
            $table->unsignedBigInteger('tbl_wbs')->default(1)->comment = '1-tbl_wbs_kit_issuance, 2-tbl_sakidashi_kit_issuance';
            $table->unsignedBigInteger('for_emboss')->default(0)->comment = '0-false, 1-true';
            $table->unsignedBigInteger('status')->default(1)->comment = '1-active, 2-inactive';

            // Foreign Keys
            $table->foreign('prod_runcard_id')->references('id')->on('production_runcards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prod_runcard_material_lists');
    }
}
