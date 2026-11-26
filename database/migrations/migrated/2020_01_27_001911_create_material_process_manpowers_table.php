<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialProcessManpowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_process_manpowers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mat_proc_id');
            $table->unsignedBigInteger('manpower_id');
            $table->integer('shift')->comment = '1=A-shift, 2=B-shift';
            $table->unsignedTinyInteger('status')->default(1)->comment = '1-active,2-inactive';
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_updated_by');
            $table->integer('update_version');
            $table->timestamps();

            // Foreign Key
            $table->foreign('mat_proc_id')->references('id')->on('material_processes');
            $table->foreign('manpower_id')->references('id')->on('users');
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
        Schema::dropIfExists('material_process_manpowers');
    }
}
