<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOqcPackingInspectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oqc_packing_inspections', function (Blueprint $table) {
            $table->increments('id');

            $table->string('po_num',30);
            $table->string('pack_code_no',30);

            //inspection checkpoints
            $table->unsignedTinyInteger('document_compliance')->nullable();
            $table->unsignedTinyInteger('accessory_requirement')->nullable();
            $table->unsignedTinyInteger('coc_requirement')->nullable();
            $table->string('emp_id', 30);

            //shipping details
            $table->string('shipping_date')->nullable();
            $table->string('shipping_destination')->nullable();
            $table->string('shipping_remarks')->nullable();

            $table->integer('status');

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
        Schema::dropIfExists('oqc_packing_inspections');
    }
}
