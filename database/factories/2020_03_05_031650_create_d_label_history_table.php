<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDLabelHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_label_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('d_label_id')->nullable();
            $table->unsignedBigInteger('unique_no')->nullable();
            $table->unsignedBigInteger('ship_boxing')->nullable();
            $table->text('lot_numbers')->nullable();
            $table->string('packing_code',10)->nullable();
            $table->unsignedTinyInteger('removed')->default(0)->nullable()->comment('0=active, 1=removed');
            $table->text('remarks')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign Key
            $table->foreign('d_label_id')->references('id')->on('d_label');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('d_label_history');
    }
}
