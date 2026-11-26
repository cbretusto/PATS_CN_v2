<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubKittingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_kittings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pats_kitting_id');
            $table->string('sub_kit_desc');
            $table->double('sub_kit_qty');
            $table->tinyInteger('status');
            $table->timestamps();

            $table->foreign('pats_kitting_id')->references('id')->on('kittings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_kittings');
    }
}
