<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateC3LabelHuaweisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c3_label_huaweis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('item_code')->nullable();
            $table->string('pn');
            $table->string('mpn');
            $table->string('qty');
            $table->string('date');
            $table->string('lotno');
            $table->string('lotno_extension');
            $table->string('print_lot');
            $table->string('print_lot_quantity');
            $table->string('unique_number');
            $table->string('max_row')->nullable();
            $table->string('date_encoded')->nullable();
            $table->string('lastupdate')->nullable();
            $table->string('username')->nullable();
            $table->string('emp_number')->nullable();
            $table->tinyInteger('partial')->default(0);
            $table->string('logdel')->default(0);


            // C3 Label Column
            $table->string('issuance_no')->nullable();
            $table->string('po_no')->nullable();
            $table->string('device_code')->nullable();
            $table->string('device_name')->nullable();
            $table->string('kit_qty')->nullable();
            $table->string('customer_pn')->nullable();
            $table->string('boxing')->nullable();
            $table->string('machine_code')->nullable();
            $table->tinyInteger('print_type')->nullable()->default(0)->comment = "1=normal print, 2=for void";
            
            // Defaults
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('c3_label_huaweis');
    }
}
