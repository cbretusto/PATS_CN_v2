<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingOperatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_operators', function (Blueprint $table) {
            $table->increments('id');

              $table->string('po_num', 30);
              $table->string('batch', 30);
              $table->string('submission', 30);
              $table->string('lotapp_fkid', 30);
              $table->string('pack_code_no', 30);

              //table 3.1
               $table->unsignedTinyInteger('radio3_1_1')->comment = '1-YES,2-NO,3-N/A';

               $table->unsignedTinyInteger('radio3_1_2')->comment = '1-YES,2-NO,3-N/A';

               $table->unsignedTinyInteger('radio3_1_3')->comment = '1-YES,2-NO,3-N/A';

               $table->unsignedTinyInteger('radio3_1_4')->comment = '1-YES,2-NO,3-N/A';

               //table 3.2
                $table->integer('radio3_2_1')->comment = '1-YES,2-NO,3-N/A';

                $table->integer('radio3_2_2')->comment = '1-YES,2-NO,3-N/A';

                $table->integer('radio3_2_3')->comment = '1-YES,2-NO,3-N/A';

                //3.3 Packing List Control Number
               $table->string('pack_list_con_no', 30);

               //3.4 Packing Code Number
               $table->string('total_shipment_qty', 30);

               //3.5 Packing Code Number
               $table->string('total_box_qty', 30);

               //employee number - employee name
               $table->string('emp_id', 30);


               $table->unsignedTinyInteger('oqc_judgement')->comment = '1-accepted,2-denied';

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
        Schema::dropIfExists('shipping_operators');
    }
}
