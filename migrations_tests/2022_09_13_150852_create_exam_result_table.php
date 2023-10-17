<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_result', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('result_hash_id');
            $table->string('std_id', 20);
            $table->integer('class_id');
            $table->integer('section_id');
            $table->integer('subject_id');
            $table->integer('academic_year');
            $table->integer('exam_id')->index('exam_id');
            $table->double('written');
            $table->double('mcq');
            $table->double('practical');
            $table->double('viva');
            $table->double('mark_obtain');
            $table->dateTime('entry_date')->nullable();
            $table->dateTime('update_date')->nullable();
            $table->string('entry_by', 11);
            $table->string('update_by', 11);
            $table->integer('result_status');
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
        Schema::dropIfExists('exam_result');
    }
}
