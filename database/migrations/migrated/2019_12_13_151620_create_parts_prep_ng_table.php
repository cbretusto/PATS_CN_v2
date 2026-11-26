<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartsPrepNgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts_prep_ng', function (Blueprint $table) {
            $table->increments('id');
            $table->string('po_no',15)->nullable();
            $table->unsignedInteger('shipment_qty')->nullable();
            $table->date('shipment_date')->nullable();
            $table->string('delivery_place_name',10)->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

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
        Schema::dropIfExists('parts_prep_ng');
    }
}
