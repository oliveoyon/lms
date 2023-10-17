<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrFeeHeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_fee_head', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('tr_feehead_hash_id');
            $table->string('tr_feehead_name', 50);
            $table->string('tr_feehead_description', 100);
            $table->integer('tr_feehed_freq');
            $table->integer('no_of_installment');
            $table->integer('tr_feehead_status');
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
        Schema::dropIfExists('tr_fee_head');
    }
}
