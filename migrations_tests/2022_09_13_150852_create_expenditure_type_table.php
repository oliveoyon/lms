<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenditureTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenditure_type', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('extype_hash_id');
            $table->string('extype_name', 50);
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
        Schema::dropIfExists('expenditure_type');
    }
}
