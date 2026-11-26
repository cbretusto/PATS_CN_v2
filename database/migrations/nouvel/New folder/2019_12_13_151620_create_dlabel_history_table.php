<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDlabelHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dlabel_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('shipment_confirmation_id')->nullable();
            $table->string('po_no',20)->nullable();
            $table->date('shipment_date')->nullable();
            $table->string('delivery_place_name',10)->nullable();
            $table->string('pack_code_no_arr')->nullable()->comment('from packing_inspector table');
            $table->unsignedBigInteger('unique_no_start')->nullable();
            $table->unsignedBigInteger('total_qty')->nullable();
            $table->unsignedBigInteger('po_qty')->nullable();
            $table->unsignedBigInteger('po_qty_bal')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('dlabel_history');
    }
}
