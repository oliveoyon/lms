<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamRoutineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_routine', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('exam_routine_hash_id');
            $table->integer('exam_id')->index('exam_id');
            $table->integer('class_id');
            $table->integer('subject_id');
            $table->date('exam_date');
            $table->string('start_time', 20);
            $table->string('end_time', 20);
            $table->double('full_mark');
            $table->double('pass_mark');
            $table->integer('academic_year');
            $table->integer('exam_routine_status');
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
        Schema::dropIfExists('exam_routine');
    }
}
