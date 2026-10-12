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
            $table->string('assess_num',25)->nullable();
            $table->unsignedInteger('assessed_qty')->default(0);
            $table->string('x_drawing_num',50)->nullable();
            $table->string('other_docs_num',50)->nullable();
            $table->text('remarks')->nullable();
            $table->integer('discrepant_qty')->default(0);
            $table->text('analysis')->nullable();
            $table->unsignedInteger('recount_ok')->default(0);
            $table->unsignedInteger('recount_ng')->default(0);
            $table->string('verified_by',25)->nullable();
            $table->string('checked_by_prod',25)->nullable();
            $table->string('checked_by_qc',25)->nullable();
            $table->string('created_by',25)->nullable();
            $table->string('updated_by',25)->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('checked_at_prod')->nullable();
            $table->dateTime('checked_at_qc')->nullable();
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
