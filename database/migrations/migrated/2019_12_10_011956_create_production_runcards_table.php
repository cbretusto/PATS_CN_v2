<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionRuncardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_runcards', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->unsignedInteger('wbs_kit_issuance_id')->nullable();
            // $table->unsignedInteger('wbs_sakidashi_issuance_id')->nullable();
            $table->string('po_no', 25);
            $table->unsignedTinyInteger('status')->default(0)->comment = '0-pending,1-passed,2-failed';
            $table->string('runcard_no', 25)->unique();
            $table->unsignedBigInteger('has_emboss')->default(0)->comment = '0-false, 1-true';
            $table->integer('lot_qty');
            $table->string('assessment_no', 25);
            // $table->string('po_no', 25);
            // $table->integer('po_qty')->default(0);
            $table->string('a_drawing_no', 25);
            $table->string('a_drawing_rev', 25);
            $table->string('g_drawing_no', 25);
            $table->string('g_drawing_rev', 25);
            $table->string('other_docs_no', 25);
            $table->string('other_docs_rev', 25);
            $table->integer('discrepant_qty')->nullable();
            $table->text('analysis')->nullable();
            $table->unsignedInteger('recount_ok')->nullable();
            $table->unsignedInteger('recount_ng')->nullable();
            $table->integer('supervisor_prod')->nullable();
            $table->integer('supervisor_qc')->nullable();
            $table->string('comp_under_runcard_no')->nullable();
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_updated_by');
            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('last_updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_runcards');
    }
}
