<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionRuncardStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_runcard_stations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('production_runcard_id');
            $table->unsignedBigInteger('mat_proc_id');
            $table->unsignedBigInteger('station_id');
            $table->unsignedBigInteger('sub_station_id');
            $table->integer('step_num');
            $table->string('operator')->nullable();

            $table->unsignedTinyInteger('status')->default(0)->comment = '0-pending,1-passed,2-failed';

            $table->unsignedBigInteger('has_emboss')->default(0)->comment = '0-false, 1-true';
            $table->string('machines')->nullable();
            $table->integer('qty_input')->nullable();
            $table->integer('qty_output')->nullable();
            $table->integer('qty_ng')->nullable();
            $table->text('mod')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_updated_by');
            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('production_runcard_id')->references('id')->on('production_runcards');
            $table->foreign('station_id')->references('id')->on('stations');
            $table->foreign('mat_proc_id')->references('id')->on('material_processes');
            $table->foreign('sub_station_id')->references('id')->on('sub_stations');
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
        Schema::dropIfExists('production_runcard_stations');
    }
}
