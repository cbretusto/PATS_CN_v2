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
            $table->unsignedTinyInteger('status')->default(0)->comment = '0-pending,1-passed,2-failed,3-done MH fill-in,4-done PPM fill-in,5-ok verification,6-not ok verification,7-approved,8-disapproved';
            $table->string('runcard_po',10);
            $table->unsignedSmallInteger('runcard_number',3);
            $table->string('assess_num',25)->nullable();
            $table->string('special_instruction',50)->nullable();
            $table->text('remarks')->nullable();

            $table->string('material_drawing_revision_number',50)->nullable();
            $table->string('sgc_doc_number',50)->nullable();
            $table->string('sgc_doc_number_revision_number',50)->nullable();
            $table->string('other_docs_num',50)->nullable();
            $table->text('parts_prep_remarks')->nullable();

            $table->integer('discrepant_qty')->default(0);
            $table->text('analysis')->nullable();
            $table->unsignedInteger('recount_ok')->default(0);
            $table->unsignedInteger('recount_ng')->default(0);
            $table->string('other_runcard_number',50)->nullable();

            $table->unsignedInteger('received_by')->nullable();
            $table->unsignedInteger('verified_by')->nullable();
            $table->unsignedInteger('checked_by_prod')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            
            $table->dateTime('received_at')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('checked_at_prod')->nullable();
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
