<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdRuncardStationMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_runcard_station_machines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('production_runcard_id');
            $table->unsignedBigInteger('production_runcard_station_id');
            $table->unsignedBigInteger('machine_id');
            
            // Foreign Keys
            $table->foreign('production_runcard_id')->references('id')->on('production_runcards');
            // $table->foreign('production_runcard_station_id')->references('id')->on('production_runcard_stations'); //Connection not working, an error occured during migration.
            $table->foreign('machine_id')->references('id')->on('machines');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prod_runcard_station_machines');
    }
}
