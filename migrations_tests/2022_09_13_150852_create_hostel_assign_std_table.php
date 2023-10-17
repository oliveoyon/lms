<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostelAssignStdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hostel_assign_std', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('hostel_assign_hash_id');
            $table->integer('std_id');
            $table->integer('hostel_id');
            $table->integer('room_id');
            $table->integer('hostel_feehead_id');
            $table->string('academic_year', 10);
            $table->integer('hostel_assign_status');
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
        Schema::dropIfExists('hostel_assign_std');
    }
}
