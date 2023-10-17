<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeCollectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_collection', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('fee_collection_hash_id');
            $table->string('std_id', 15);
            $table->integer('fee_group_id');
            $table->integer('aca_feehead_id');
            $table->integer('aca_feeamount_id');
            $table->double('payable_amount');
            $table->double('paid_amount');
            $table->dateTime('paid_date');
            $table->date('inst_first_date');
            $table->date('inst_last_date');
            $table->integer('academic_year');
            $table->integer('is_billgen');
            $table->integer('is_paid');
            $table->string('feecolid', 4);
            $table->double('discount');
            $table->integer('fee_collection_status');
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
        Schema::dropIfExists('fee_collection');
    }
}
