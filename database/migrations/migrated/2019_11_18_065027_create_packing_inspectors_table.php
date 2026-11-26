<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackingInspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packing_inspectors', function (Blueprint $table) {
            $table->increments('id');

              $table->string('po_num', 30);
              $table->string('batch', 30);
              $table->string('submission', 30);
              $table->string('lotapp_fkid', 30);

            //2.1 OQC Acceptance Stamp
             $table->unsignedTinyInteger('oqc_acceptance_stamp')->comment = '1-YES,2-NO,3-N/A';

             //table 2.2
               $table->unsignedTinyInteger('radio2_2_1')->comment = '1-YES,2-NO,3-N/A';

               $table->unsignedTinyInteger('radio2_2_2')->comment = '1-YES,2-NO,3-N/A';

               $table->unsignedTinyInteger('radio2_2_3')->comment = '1-YES,2-NO,3-N/A';

               $table->unsignedTinyInteger('radio2_2_4')->comment = '1-YES,2-NO,3-N/A';

               $table->string('total_num_reels',11)->nullable();

               ///2.3 Packing Manual Document Compliance
                $table->unsignedTinyInteger('pac_man_doc_comp')->comment = 'Packing Manual Document:1-YES,2-NO,3-N/A';

                //2.4 Accessories
               $table->unsignedTinyInteger('accessories')->comment = '1-YES,2-NO,3-N/A';

                 //1.4 Packing Code Number
               $table->string('pack_code_no', 30);

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
        Schema::dropIfExists('packing_inspectors');
    }
}
