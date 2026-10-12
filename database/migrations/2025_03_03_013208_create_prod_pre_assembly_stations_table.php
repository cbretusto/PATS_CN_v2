<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdPreAssemblyStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_pre_assembly_stations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pre_assembly_id');
            $table->unsignedBigInteger('mat_proc_id');
            $table->unsignedBigInteger('station_id');
            $table->unsignedBigInteger('sub_station_id');
            $table->integer('step_num');
            $table->integer('qty_input')->nullable();
            $table->integer('qty_output')->nullable();
            $table->integer('qty_ng')->nullable();
            $table->longText('remarks')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('prod_pre_assembly_stations');
    }
}
