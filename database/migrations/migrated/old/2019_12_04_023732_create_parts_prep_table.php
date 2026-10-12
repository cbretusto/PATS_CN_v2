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
            $table->increments('id');
            $table->unsignedInteger('wbs_kit_issuance_id');
            $table->unsignedTinyInteger('status')->default(0)->comment = '0-pending,1-passed,2-failed';
            $table->string('assess_num',25)->nullable();
            $table->unsignedInteger('assessed_qty')->default(0);
            $table->string('x_drawing_num',50)->nullable();
            $table->string('other_docs_num',50)->nullable();
            $table->text('remarks')->nullable();
            $table->integer('discrepant_qty')->default(0);
            $table->text('analysis')->nullable();
            $table->unsignedInteger('recount_ok')->default(0);
            $table->unsignedInteger('recount_ng')->default(0);
            $table->string('supervisor_prod',25)->nullable();
            $table->string('supervisor_qc',25)->nullable();
            $table->string('created_by',25)->nullable();
            $table->string('updated_by',25)->nullable();
            $table->timestamps();
            $table->softDeletes();
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
