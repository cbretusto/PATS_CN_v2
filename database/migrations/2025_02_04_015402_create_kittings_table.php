<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKittingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kittings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kit_issuance_id');
            $table->string('po_no', 30);
            $table->string('issue_no', 30);
            $table->string('item', 50);
            $table->string('item_desc', 50);
            $table->tinyInteger('status');
            $table->double('sub_kit_qty');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_updated_by');
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
        Schema::dropIfExists('kittings');
    }
}
