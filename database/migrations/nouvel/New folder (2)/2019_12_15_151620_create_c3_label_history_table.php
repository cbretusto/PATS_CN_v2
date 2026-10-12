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
            $table->unsignedBigInteger('c3_label_id');
            $table->string('customer_pn',25)->nullable();
            $table->string('manufacture_pn',25)->nullable();
            $table->unsignedInteger('lot_qty')->nullable();
            $table->string('date_code',4)->nullable();
            $table->string('lot_number',8)->nullable();
            $table->unsignedTinyInteger('lot_number_ctr')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Foreign Key
            $table->foreign('c3_label_id')->references('id')->on('c3_label');

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
