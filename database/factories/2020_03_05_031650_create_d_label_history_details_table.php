<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDLabelHistoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('d_label_history_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('d_label_history_id')->nullable();
            $table->unsignedTinyInteger('print_type')->nullable()->comment('0=normal print, 1=reprint');
            $table->text('remarks')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Foreign Key
            $table->foreign('d_label_history_id')->references('id')->on('d_label_history');
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
        Schema::dropIfExists('d_label_history_details');
    }
}
