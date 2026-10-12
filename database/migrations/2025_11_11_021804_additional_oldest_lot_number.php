<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdditionalOldestLotNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_runcards', function (Blueprint $table) {
            $table->string('oldest_lot_no')->nullable()->after('print_lots')->comment = "For C3 labeller use";
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
