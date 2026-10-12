<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartsPrepTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts_preps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('wbs_kit_issuance_id');
            $table->string('assess_num',25)->nullable();
            $table->unsignedInteger('assessed_qty')->default(0);
            $table->string('x_drawing_num',50)->nullable();
            $table->string('other_docs_num',50)->nullable();
            $table->text('remarks')->nullable();
            $table->integer('discrepant_qty')->default(0);
            $table->text('analysis')->nullable();
            $table->unsignedInteger('recount_ok')->default(0);
            $table->unsignedInteger('recount_ng')->default(0);

            $table->unsignedInteger('verified_by')->nullable();
            $table->unsignedInteger('checked_by_prod')->nullable();
            $table->unsignedInteger('checked_by_qc')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('checked_at_prod')->nullable();
            $table->dateTime('checked_at_qc')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key
            // $table->foreign('verified_by')->references('id')->on('users');
            // $table->foreign('checked_by_prod')->references('id')->on('users');
            // $table->foreign('checked_by_qc')->references('id')->on('users');
            // $table->foreign('created_by')->references('id')->on('users');
            // $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parts_preps');
    }
}
