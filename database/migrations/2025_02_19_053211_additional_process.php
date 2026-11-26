<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdditionalProcess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('material_processes', function (Blueprint $table) {
            //
            $table->smallInteger('has_printing')->default(0)->after('require_oqc_before_emboss');
            $table->smallInteger('has_reel_input')->default(0)->after('has_printing');
            $table->smallInteger('has_print_input')->default(0)->after('has_reel_input');
            $table->smallInteger('has_guaranteed')->default(0)->after('has_print_input');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('material_processes', function (Blueprint $table) {
            //
        });
    }
}
