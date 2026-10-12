<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateC3LabelHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c3_label_history', function (Blueprint $table) {
            $table->increments('id');
            $table->string('issuance_no',25)->nullable();
            $table->string('customer_pn',25)->nullable();
            $table->string('manufacture_pn',25)->nullable();
            $table->unsignedInteger('lot_qty')->nullable();
            $table->string('date_code',4)->nullable();
            $table->string('lot_number',8)->nullable();
            $table->unsignedTinyInteger('lot_number_ctr')->default(0);

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
        Schema::dropIfExists('c3_label_history');
    }
}
