<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateC3LabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c3_label', function (Blueprint $table) {
            $table->increments('id');
            $table->string('issuance_no',25)->nullable();
            $table->string('po_no',25)->nullable();
            $table->string('device_code',25)->nullable();
            $table->string('device_name',50)->nullable();
            $table->string('kit_qty',25)->nullable();

            $table->string('customer_pn',25)->nullable();
            $table->unsignedInteger('boxing')->nullable();
            $table->string('machine_code',1)->nullable();

            $table->unsignedTinyInteger('print_type')->default(0)->comment = '1=normal print, 2=void';

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
        Schema::dropIfExists('c3_label');
    }
}
