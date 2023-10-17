<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultGradeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_grade', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('grade_hash_id');
            $table->integer('version_id');
            $table->string('grade_name', 5);
            $table->integer('percent_from');
            $table->integer('percent_to');
            $table->double('cgpa');
            $table->integer('academic_year');
            $table->integer('grade_status');
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
        Schema::dropIfExists('result_grade');
    }
}
