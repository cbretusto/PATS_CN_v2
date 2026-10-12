<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateC3LabelHistoryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c3_label_history_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('c3_label_history_id');
            $table->unsignedTinyInteger('print_type')->default(0)->comment = '1-print all for reel or tray, 2-print all for box, 3-print individual for box, 3-reprint all, 4-reprint individual';
            $table->text('remarks')->nullable();

            $table->unsignedBigInteger('received_by')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->dateTime('received_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key
            $table->foreign('c3_label_history_id')->references('id')->on('c3_label_history');
            $table->foreign('received_by')->references('id')->on('users');
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
        Schema::dropIfExists('c3_label_history_details');
    }
}
