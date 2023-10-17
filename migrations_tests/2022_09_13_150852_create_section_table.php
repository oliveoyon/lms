<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('section', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('section_hash_id');
            $table->integer('version_id');
            $table->integer('class_id')->index('class_id');
            $table->integer('class_teacher_id')->nullable();
            $table->string('section_name', 100);
            $table->integer('max_students');
            $table->integer('section_status');
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
        Schema::dropIfExists('section');
    }
}
