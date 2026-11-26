<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOqcvir extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oqcvir', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fkid_oqclotapp', 30);
            $table->unsignedTinyInteger('status')->comment = '0 = On-going inspection, 1 = Done';
            $table->string('oqc_sample');
            $table->string('ok_qty');
            $table->string('ng_qty');
            $table->date('insp_date');
            $table->time('insp_stime');
            $table->time('insp_etime');
            $table->string('insp_name');
            $table->unsignedTinyInteger('acc_req')->comment = '1-Yes,2-No';
            $table->unsignedTinyInteger('coc_req')->comment = '1-Yes,2-No';
            $table->unsignedTinyInteger('result')->comment = '1-NO DEFECT FOUND,2-WITH DEFECT FOUND / DETAILS';
            $table->unsignedTinyInteger('judgement')->comment = '1-ACCEPTED,2-REJECTED';
            $table->string('remarks')->nullable();

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
        Schema::dropIfExists('oqcvir');
    }
}
