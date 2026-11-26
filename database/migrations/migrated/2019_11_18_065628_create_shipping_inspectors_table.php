<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingInspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_inspectors', function (Blueprint $table) {
            $table->increments('id');

            $table->string('po_num', 30);
            $table->string('batch', 30);
            $table->string('submission', 30);
            $table->string('lotapp_fkid', 30);
            $table->string('pack_code_no', 30);

            //4.1 ROHS Sticker
            $table->unsignedTinyInteger('radio4_1')->comment = '1-YES,2-NO,3-N/A';

            //Table 4.2
            $table->unsignedTinyInteger('radio4_2_1')->comment = '1-YES,2-NO,3-N/A';
            $table->unsignedTinyInteger('radio4_2_2')->comment = '1-YES,2-NO,3-N/A';
            $table->unsignedTinyInteger('radio4_2_3')->comment = '1-YES,2-NO,3-N/A';
            $table->unsignedTinyInteger('radio4_2_4')->comment = '1-YES,2-NO,3-N/A';
         
            //Table 4.3
             //4.3.1  D-Label
               $table->string('input4_3_1', 30)->comment = 'dlabel';

             //4.3.2  OQC Lot App
               $table->string('input4_3_2', 30)->comment = 'oqc lot app';

             //4.3.3 Upper Right Portion of the Box
               $table->string('input4_3_3', 30)->comment = 'upper right portion of the box';

               //4.4 COC
            $table->unsignedTinyInteger('radio4_4')->comment = 'coc, 1-YES,2-NO,3-N/A';

            //Table 4.5
             //4.5.1  P.O. Number
               $table->string('input4_5_1', 30)->comment = 'po number';

             //4.5.2  Device Name
               $table->string('input4_5_2', 30)->comment = 'device name';

             //4.5.3 Total Quantity
               $table->string('input4_5_3', 30)->comment = 'total quantity';

             //4.5.4  Destination
               $table->string('input4_5_4', 30)->comment = 'destination';

             //4.5.5  Carton Box Number
               $table->string('input4_5_5', 30)->comment = 'carton box number';

             //4.5.6 PMI Transaction Number
               $table->string('input4_5_6', 30)->comment = 'pmi transaction number';

            //Table 4.6
             //4.6.1  Packing List Control Number
               $table->string('input4_6_1', 30)->comment = 'packing list control number';

             //4.6.2 Total Shipment Quantity
               $table->string('input4_6_2', 30)->comment = 'total shipment quantity';

             //4.6.3 Total Box Quantity
               $table->string('input4_6_3', 30)->comment = 'total box quantity';

                //4.7 QC vs Masterbox
            $table->unsignedTinyInteger('radio4_7')->comment = '1-YES,2-NO,3-N/A';

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
        Schema::dropIfExists('shipping_inspectors');
    }
}
