<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrFeeHeadAmountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_fee_head_amount', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('fee_amount_hash_id');
            $table->integer('tr_feehead_id');
            $table->double('amount');
            $table->integer('class_id');
            $table->integer('fee_amount_status');
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
        Schema::dropIfExists('tr_fee_head_amount');
    }
}
