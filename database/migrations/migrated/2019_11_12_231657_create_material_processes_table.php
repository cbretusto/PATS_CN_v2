<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_processes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('step');
            // $table->string('material', 255);
            $table->unsignedTinyInteger('status')->default(1)->comment = '1-active,2-inactive';
            $table->unsignedBigInteger('device_id');
            $table->unsignedBigInteger('station_sub_station_id');
            // $table->unsignedBigInteger('machine_id')->nullable();
            $table->unsignedBigInteger('has_emboss')->default(0)->comment = '0-false, 1-true';
            $table->unsignedBigInteger('require_oqc_before_emboss')->default(0)->comment = '0-false, 1-true';
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_updated_by');
            $table->integer('update_version');
            $table->timestamps();

            // Foreign Key
            $table->foreign('device_id')->references('id')->on('devices');
            $table->foreign('station_sub_station_id')->references('id')->on('station_sub_stations');
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
        Schema::dropIfExists('material_processes');
    }
}
