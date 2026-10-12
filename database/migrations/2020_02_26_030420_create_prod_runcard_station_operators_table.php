<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdRuncardStationOperatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_runcard_station_operators', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('production_runcard_id');
            $table->unsignedBigInteger('production_runcard_station_id');
            $table->unsignedBigInteger('operator_id');
            
            // Foreign Keys
            $table->foreign('production_runcard_id')->references('id')->on('production_runcards');
            // $table->foreign('production_runcard_station_id')->references('id')->on('production_runcard_stations'); //Connection not working, an error occured during migration.
            $table->foreign('operator_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prod_runcard_station_operators');
    }
}
