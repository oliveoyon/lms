<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsAcademicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_academic', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('std_hash_id');
            $table->string('std_id', 15);
            $table->integer('version_id');
            $table->integer('class_id');
            $table->integer('section_id');
            $table->integer('roll_no');
            $table->string('academic_year', 4);
            $table->string('std_password', 300);
            $table->integer('st_aca_status');
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
        Schema::dropIfExists('students_academic');
    }
}
