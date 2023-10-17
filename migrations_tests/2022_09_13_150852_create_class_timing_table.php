<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassTimingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_timing', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('ct_hash_id');
            $table->integer('class_id');
            $table->integer('section_id');
            $table->string('academic_year', 4);
            $table->string('days', 15);
            $table->string('period1', 100);
            $table->string('period2', 100);
            $table->string('period3', 100);
            $table->string('period4', 100);
            $table->string('period5', 100);
            $table->string('period6', 100);
            $table->string('period7', 100);
            $table->string('period8', 100);
            $table->string('period9', 100);
            $table->string('period10', 100);
            $table->integer('status');
            $table->string('plists', 30);
            $table->string('subjects', 30);
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
        Schema::dropIfExists('class_timing');
    }
}
