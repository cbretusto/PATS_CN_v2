<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOqclotappTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oqclotapp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('po_no', 30);
            $table->unsignedTinyInteger('status')->comment = '0 = Prodn Approval, 1 = QC Approval, 2 = Done';
            $table->unsignedTinyInteger('device_cat')->comment = '1-Automotive,2-Non-Automotive';
            $table->unsignedTinyInteger('cert_lot')->comment = '1-New Operator,2-New product/model,3-Evaluation lot,4-Re-inspection,5-Flexibility';
            $table->string('assy_line', 50);
            $table->string('lot_batch_no');
            $table->string('FVO_empid', 20);
            $table->string('reel_lot')->nullable();
            $table->string('print_lot');
            $table->string('lot_qty');
            $table->string('direction')->nullable();
            $table->string('drawing', 100);
            $table->string('ttl_reel')->nullable();
            $table->date('app_date');
            $table->time('app_time');
            $table->unsignedTinyInteger('guaranteed_lot')->comment = '1-With,2-Without';
            $table->string('problem')->nullable();
            $table->string('doc_no')->nullable();
            $table->string('remarks')->nullable();
            $table->string('prodn_supervisor')->nullable();
            $table->string('oqc_supervisor')->nullable();

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
        Schema::dropIfExists('oqclotapp');
    }
}
