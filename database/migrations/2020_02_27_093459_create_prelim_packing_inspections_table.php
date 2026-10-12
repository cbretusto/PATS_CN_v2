<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrelimPackingInspectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prelim_packing_inspections', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('po_num',30);
            $table->string('pack_code_no',30);

            $table->unsignedTinyInteger('packing_type')->comment = '1 - Box/Esafoam, 2 - Magazine Tube, 3 - Tray, 4 - Bubble Sheet, 5 - Emboss Reel, 6 - Polybag'; //1.1 
            $table->unsignedTinyInteger('packing_unit_condition')->comment = '1 - Terminal Up, 2 - Terminal Down, 3 - Terminal Mounted on Esafoam, 4 - Terminal Side, 5 - Unit Mounted on Emboss Pocket, 6 - Wrap on Bubble Sheet';

            $table->unsignedTinyInteger('orientation_of_units');
            $table->unsignedTinyInteger('qty_per_box_tray');
            $table->unsignedTinyInteger('ul_sticker');
            $table->unsignedTinyInteger('silica_gel');
            $table->unsignedTinyInteger('accessories');
            $table->unsignedTinyInteger('rohs_sticker');

            $table->string('emp_id', 30);

            $table->unsignedTinyInteger('operator_judgement')->comment = '1-accepted,2-denied';

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
        Schema::dropIfExists('prelim_packing_inspections');
    }
}
