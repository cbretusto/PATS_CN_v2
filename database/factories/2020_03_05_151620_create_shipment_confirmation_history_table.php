<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentConfirmationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_confirmation_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('shipment_confirmation_id')->nullable();

            $table->string('po_no',20)->nullable();
            $table->date('shipment_date')->nullable();
            $table->string('delivery_place_name',10)->nullable();
            $table->unsignedInteger('shipment_qty')->nullable();
            $table->text('remarks')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign Key
            $table->foreign('shipment_confirmation_id')->references('id')->on('shipment_confirmation');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipment_confirmation_history');
    }
}
