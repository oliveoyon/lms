<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrAssignStdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_assign_std', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('tr_assign_hash_id');
            $table->integer('std_id');
            $table->integer('rout_id');
            $table->integer('pickup_stopage');
            $table->integer('drop_stopage');
            $table->integer('tr_feehead_id');
            $table->string('academic_year', 10);
            $table->integer('tr_assign_status');
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
        Schema::dropIfExists('tr_assign_std');
    }
}
