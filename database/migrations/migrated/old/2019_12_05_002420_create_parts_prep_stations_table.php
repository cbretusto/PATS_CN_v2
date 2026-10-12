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
            $table->increments('id');
            $table->unsignedInteger('parts_prep_id');
            $table->unsignedInteger('station_id');
            $table->unsignedInteger('sub_station_id');
            $table->unsignedInteger('step_num');

            $table->unsignedTinyInteger('status')->default(0)->comment = '0-pending,1-passed,2-failed';
            $table->unsignedTinyInteger('notapplicable')->default(0)->comment = '0-default-no,1-yes';

            $table->unsignedInteger('qty_input')->default(0);
            $table->unsignedInteger('qty_output')->default(0);
            $table->unsignedInteger('qty_ng')->default(0);
            $table->text('mod')->nullable();

            $table->string('created_by',25)->nullable();
            $table->string('updated_by',25)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key
            $table->foreign('parts_prep_id')->references('id')->on('parts_preps');
            $table->foreign('station_id')->references('id')->on('stations');
            $table->foreign('sub_station_id')->references('id')->on('sub_stations');
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
