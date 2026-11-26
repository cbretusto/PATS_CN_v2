<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOqcVirLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oqc_vir_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fkid_oqclotapp', 30);
            $table->string('c_lot_batch_no')->nullable();
            $table->string('c_sub_lot_no')->nullable();
            $table->string('packing_code')->nullable();
            $table->unsignedTinyInteger('status')->nullable()->comment = '0 = On-going inspection, 1 = Done';
            $table->string('submission')->nullable();
            $table->string('oqc_sample')->nullable();
            $table->string('ok_qty')->nullable();
            $table->string('ng_qty')->nullable();
            $table->date('insp_date')->nullable();
            $table->time('insp_stime')->nullable();
            $table->time('insp_etime')->nullable();
            $table->string('insp_name')->nullable();
            $table->string('insp_stamp')->nullable();
            $table->unsignedTinyInteger('acc_req')->nullable()->comment = '1-Yes,2-No';
            $table->unsignedTinyInteger('coc_req')->nullable()->comment = '1-Yes,2-No';
            $table->unsignedTinyInteger('result')->nullable()->comment = '1-NO DEFECT FOUND,2-WITH DEFECT FOUND / DETAILS';
            $table->unsignedTinyInteger('judgement')->nullable()->comment = '1-ACCEPTED,2-REJECTED';
            $table->string('remarks')->nullable();
            $table->string('controller_function')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

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
        Schema::dropIfExists('oqc_vir_logs');
    }
}
