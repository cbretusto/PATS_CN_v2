<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartsPrepStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts_prep_stations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parts_prep_id');
            $table->unsignedBigInteger('station_id');
            $table->unsignedBigInteger('sub_station_id');
            $table->unsignedInteger('step_num');
            $table->unsignedBigInteger('machine_id')->nullable();

            $table->unsignedInteger('qty_input')->default(0);
            $table->unsignedInteger('qty_output')->default(0);
            $table->text('qty_ng')->nullable();
            $table->text('mod')->nullable();
            $table->string('print_code',50)->nullable();

            $table->unsignedBigInteger('setup_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
            $table->dateTime('setup_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key
            $table->foreign('parts_prep_id')->references('id')->on('parts_preps');
            $table->foreign('station_id')->references('id')->on('stations');
            $table->foreign('sub_station_id')->references('id')->on('sub_stations');
            // $table->foreign('machines_id')->references('id')->on('machines');
            // $table->foreign('setup_by')->references('id')->on('users');
            // $table->foreign('created_by')->references('id')->on('users');
            // $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parts_prep_stations');
    }
}
