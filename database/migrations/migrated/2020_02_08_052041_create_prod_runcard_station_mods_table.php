<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdRuncardStationModsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_runcard_station_mods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('production_runcard_id');
            $table->unsignedBigInteger('production_runcard_station_id');
            $table->unsignedBigInteger('mod_id');
            $table->integer('mod_qty');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_updated_by');
            $table->integer('update_version');
            $table->unsignedTinyInteger('status')->default(1)->comment = '1-active,2-inactive';
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('production_runcard_id')->references('id')->on('production_runcards');
            $table->foreign('production_runcard_station_id')->references('id')->on('production_runcard_stations');
            $table->foreign('mod_id')->references('id')->on('mode_of_defects');
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
        Schema::dropIfExists('prod_runcard_station_mods');
    }
}
