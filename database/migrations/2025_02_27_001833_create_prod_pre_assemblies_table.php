<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdPreAssembliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_pre_assemblies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('status')->default(0)->comment = "0-Pending";
            $table->string('po_no');
            $table->bigInteger('lot_qty');
            $table->string('pre_assy_no')->nullable();
            $table->string('pre_assy_station_code')->nullable()->comment = "from table stations";
            $table->string('other_docs_number')->nullable();
            $table->string('revision')->nullable();
            $table->longText('remarks')->nullable();
            $table->smallInteger('print_status')->default(0)->comment = "0-Not Printed";
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prod_pre_assemblies');
    }
}
