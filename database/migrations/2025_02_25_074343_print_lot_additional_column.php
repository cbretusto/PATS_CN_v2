<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PrintLotAdditionalColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_runcards', function (Blueprint $table) {
            $table->json('print_lots')->nullable()->after('sel_product_category');
            $table->smallInteger('has_guaranteed_lot')->nullable()->after('print_lots')->comment = "1-with, 2-without";
            $table->string('guaranteed_lot_problem')->nullable()->after('has_guaranteed_lot');
            $table->string('guaranteed_lot_doc_no')->nullable()->after('guaranteed_lot_problem');
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
