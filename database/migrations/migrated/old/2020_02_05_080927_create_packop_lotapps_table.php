<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackopLotappsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packop_lotapps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('packop_id');
            $table->unsignedBigInteger('lotapp_id');
            $table->unsignedBigInteger('status')->default(1)->comment = '1 - Active, 2 - Inactive';
            $table->timestamps();

            $table->foreign('packop_id')->references('id')->on('packing_operators');
            $table->foreign('lotapp_id')->references('id')->on('oqclotapp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packop_lotapps');
    }
}
