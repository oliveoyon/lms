<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeFrequencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_frequency', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('freq_hash_id');
            $table->string('freq_name', 20);
            $table->integer('no_of_installment');
            $table->string('installment_period', 30);
            $table->integer('freq_status');
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
        Schema::dropIfExists('fee_frequency');
    }
}
