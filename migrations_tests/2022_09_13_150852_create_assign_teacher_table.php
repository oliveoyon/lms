<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_teacher', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('assign_hash_id');
            $table->integer('version_id');
            $table->integer('class_id');
            $table->integer('section_id');
            $table->integer('subject_id');
            $table->integer('teacher_id')->index('teacher_id');
            $table->string('academic_year', 4);
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
        Schema::dropIfExists('assign_teacher');
    }
}
