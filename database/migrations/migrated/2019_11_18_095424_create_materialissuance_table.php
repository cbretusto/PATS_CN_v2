<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialissuanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materialissuance', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tbl_wbs_material_kitting_id');
            $table->unsignedTinyInteger('status')->default(0)->comment = '0-pending,1-passed,2-failed';
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key
            /*$table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materialissuance');
    }
}
