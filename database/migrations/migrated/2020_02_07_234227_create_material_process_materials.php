<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialProcessMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_process_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mat_proc_id');
            $table->string('item');
            $table->string('item_desc');
            $table->integer('tbl_wbs')->default(1)->comment = '1-tbl_wbs_material_kitting_details, 2-tbl_wbs_sakidashi_issuance_item';
            $table->unsignedTinyInteger('status')->default(1)->comment = '1-active,2-inactive';
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_updated_by');
            $table->integer('update_version');
            $table->timestamps();

            // Foreign Key
            $table->foreign('mat_proc_id')->references('id')->on('material_processes');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('last_updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_process_materials');
    }
}
