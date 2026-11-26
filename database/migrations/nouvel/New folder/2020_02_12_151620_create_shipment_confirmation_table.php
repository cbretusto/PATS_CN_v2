<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentConfirmationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_confirmation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('po_no',20)->nullable();
            $table->date('shipment_date')->nullable();
            $table->string('delivery_place_name',10)->nullable();
            $table->unsignedInteger('shipment_qty')->nullable();
            $table->text('remarks')->nullable();
            $table->text('delete_remarks')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unsignedTinyInteger('status')->default(0)
            ->comment ='1-Ongoing, 2-Cancelled, FOR CN-PPTS ONLY (Wag ida-drop itong column na to ha!)';
            
            // Foreign Key
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
        Schema::dropIfExists('shipment_confirmation');
    }
}
