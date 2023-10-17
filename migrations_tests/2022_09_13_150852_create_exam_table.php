<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('exam_hash_id');
            $table->integer('version_id');
            $table->integer('class_id');
            $table->string('exam_name', 200);
            $table->integer('academic_year');
            $table->string('description', 200);
            $table->integer('exam_status');
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
        Schema::dropIfExists('exam');
    }
}
