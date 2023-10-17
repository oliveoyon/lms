<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('teacher_hash_id');
            $table->string('teacher_name', 100);
            $table->string('teacher_user_name', 50);
            $table->string('teacher_mobile', 20);
            $table->string('teacher_email', 100);
            $table->string('teacher_designation', 100);
            $table->string('teacher_gender', 10);
            $table->string('teacher_password', 300);
            $table->string('teacher_image', 50);
            $table->integer('teacher_status');
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
        Schema::dropIfExists('teacher');
    }
}
