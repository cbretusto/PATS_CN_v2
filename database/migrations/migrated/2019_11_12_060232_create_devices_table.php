<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('huawei_p_n')->nullable();
            $table->string('lot_no_machine_code')->nullable();
            $table->string('mrp_no')->nullable();
            $table->integer('boxing');
            $table->integer('ship_boxing');
            $table->unsignedTinyInteger('status')->default(1)->comment = '1-active,2-inactive';
            $table->tinyInteger('product_type')->default(1)->comment = '1-Huawei, 2-Others';
            $table->string('barcode');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_updated_by');
            $table->integer('update_version');
            $table->timestamps();

            // Foreign Key
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
        Schema::dropIfExists('devices');
    }
}
