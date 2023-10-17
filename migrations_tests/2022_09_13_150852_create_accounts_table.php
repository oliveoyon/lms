<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('trns_hash_id');
            $table->integer('std_id')->nullable();
            $table->string('invoice_no', 150)->nullable();
            $table->string('trns_type', 150)->nullable();
            $table->string('description', 150)->nullable();
            $table->double('credit_amt')->nullable();
            $table->double('debit_amt')->nullable();
            $table->dateTime('entry_date')->nullable();
            $table->integer('academic_year')->nullable();
            $table->integer('invflag');
            $table->integer('trns_status')->nullable();
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
        Schema::dropIfExists('accounts');
    }
}
