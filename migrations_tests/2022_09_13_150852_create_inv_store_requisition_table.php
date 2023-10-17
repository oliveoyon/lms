<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvStoreRequisitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_store_requisition', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('req_hash_id');
            $table->integer('recipiennt_id');
            $table->integer('pdt_id');
            $table->string('pdt_qty', 4);
            $table->string('remarks', 200);
            $table->dateTime('submission_date');
            $table->integer('status');
            $table->integer('req_no');
            $table->integer('school_id');
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
        Schema::dropIfExists('inv_store_requisition');
    }
}
