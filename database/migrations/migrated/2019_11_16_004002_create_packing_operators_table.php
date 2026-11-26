<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackingOperatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packing_operators', function (Blueprint $table) {
            $table->increments('id');

            $table->string('po_num', 30);
            $table->string('batch', 30);
            $table->string('submission', 30);
            $table->string('lotapp_fkid', 30);

            //start of form
            $table->unsignedTinyInteger('packop_packing_type')->comment = '1 - Box/Esafoam, 2 - Magazine Tube, 3 - Tray, 4 - Bubble Sheet, 5 - Emboss Reel, 6 - Polybag'; //1.1 Packing Type

             $table->unsignedTinyInteger('packop_unit_condition')->comment = '1 - Terminal Up, 2 - Terminal Down, 3 - Terminal Mounted on Esafoam, 4 - Terminal Side, 5 - Unit Mounted on Esafoam, 6 - Wrap on Bubble Sheet'; //1.2 Unit Condition

                //table 1.3
               $table->unsignedTinyInteger('radio1_3_1')->comment = '1-YES,2-NO,3-N/A';

               $table->unsignedTinyInteger('radio1_3_2')->comment = '1-YES,2-NO,3-N/A';

               $table->unsignedTinyInteger('radio1_3_3')->comment = '1-YES,2-NO,3-N/A';

               $table->unsignedTinyInteger('radio1_3_4')->comment = '1-YES,2-NO,3-N/A';

               $table->string('total_num_reels',11)->nullable();

               //1.4 Packing Code Number
               $table->string('pack_code_no', 30);

               //Table 1.5
               $table->unsignedTinyInteger('radio1_5_1')->comment = '1-YES,2-NO,3-N/A';

               $table->unsignedTinyInteger('radio1_5_2')->comment = '1-YES,2-NO,3-N/A';

               $table->unsignedTinyInteger('radio1_5_3')->comment = '1-YES,2-NO,3-N/A';

               $table->unsignedTinyInteger('radio1_5_4')->comment = '1-YES,2-NO,3-N/A';

               $table->unsignedTinyInteger('radio1_5_5')->comment = '1-YES,2-NO,3-N/A';

               $table->unsignedTinyInteger('radio1_5_6')->comment = '1-YES,2-NO,3-N/A';

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
        Schema::dropIfExists('packing_operators');
    }
}
