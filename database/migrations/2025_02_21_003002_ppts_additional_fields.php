<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PptsAdditionalFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_runcards', function (Blueprint $table) {
            $table->string('sel_assembly_line')->nullable()->after('comp_under_runcard_no');
            $table->string('sel_certification_lot')->nullable()->after('sel_assembly_line');
            $table->string('sel_product_category')->nullable()->after('sel_certification_lot');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_runcards', function (Blueprint $table) {
            //
        });
    }
}
