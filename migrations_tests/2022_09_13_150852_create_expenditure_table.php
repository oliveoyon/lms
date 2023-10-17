<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenditureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenditure', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('exp_hash_id');
            $table->integer('extype_id');
            $table->string('purpose', 100);
            $table->string('description', 100);
            $table->string('purchase_by', 100);
            $table->string('voucher_no', 100);
            $table->double('amount');
            $table->dateTime('expenditure_date')->nullable();
            $table->integer('entry_by');
            $table->integer('payment_type');
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
        Schema::dropIfExists('expenditure');
    }
}
