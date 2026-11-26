<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdPreAssemblyStationMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_pre_assembly_station_machines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pre_assembly_id');
            $table->unsignedBigInteger('pre_assembly_station_id');
            $table->unsignedBigInteger('machine_id');
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
        Schema::dropIfExists('prod_pre_assembly_station_machines');
    }
}
