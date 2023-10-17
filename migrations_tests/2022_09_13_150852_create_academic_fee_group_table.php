<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicFeeGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_fee_group', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('aca_group_hash_id');
            $table->string('aca_group_name', 100);
            $table->string('aca_feehead_id', 200);
            $table->integer('academic_year');
            $table->integer('aca_group_status');
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
        Schema::dropIfExists('academic_fee_group');
    }
}
