<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicFeeHeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_fee_head', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('aca_feehead_hash_id');
            $table->string('aca_feehead_name', 100);
            $table->string('aca_feehead_description', 200);
            $table->string('aca_feehead_freq', 50);
            $table->integer('no_of_installment');
            $table->integer('status');
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
        Schema::dropIfExists('academic_fee_head');
    }
}
