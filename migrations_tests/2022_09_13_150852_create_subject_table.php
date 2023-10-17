<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('subject_hash_id');
            $table->integer('version_id');
            $table->integer('class_id');
            $table->string('subject_name', 200);
            $table->string('subject_code', 10);
            $table->string('academic_year', 4);
            $table->integer('subject_status');
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
        Schema::dropIfExists('subject');
    }
}
