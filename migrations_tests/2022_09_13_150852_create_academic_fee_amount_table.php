<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicFeeAmountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_fee_amount', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('aca_feeamount_hash_id');
            $table->integer('aca_group_id')->index('aca_group_id');
            $table->integer('aca_feehead_id')->index('aca_feehead_id');
            $table->double('amount');
            $table->integer('class_id');
            $table->integer('academic_year');
            $table->integer('aca_feeamount_status');
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
        Schema::dropIfExists('academic_fee_amount');
    }
}
