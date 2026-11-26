<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOqcPrelimInspectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oqc_prelim_inspections', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('po_num',30);
            $table->string('pack_code_no',30);

            //inspection checkpoints
            $table->unsignedTinyInteger('document_compliance');
            $table->unsignedTinyInteger('accessory_requirement');
            $table->unsignedTinyInteger('coc_requirement');
            $table->unsignedTinyInteger('inspector_judgement')->comment = '1-accepted,2-denied';
            $table->string('emp_id', 30);

            //shipping details
            $table->string('shipping_date');
            $table->string('shipping_destination');
            $table->string('shipping_remarks');

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
        Schema::dropIfExists('oqc_prelim_inspections');
    }
}
