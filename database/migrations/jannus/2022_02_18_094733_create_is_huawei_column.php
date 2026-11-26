<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIsHuaweiColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c3_label_history', function (Blueprint $table) {
            $table->tinyInteger('is_huawei')->nullable()->comment = "0-non, 1-huawei";
            $table->string('c3_label_huawei_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c3_label_history', function (Blueprint $table) { 
            //
        });
    }
}
