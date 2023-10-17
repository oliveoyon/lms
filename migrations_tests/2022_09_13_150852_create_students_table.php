<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('std_hash_id');
            $table->string('std_id', 15)->unique('std_id');
            $table->string('std_name', 100);
            $table->string('std_name_bn', 200);
            $table->string('academic_year', 4);
            $table->integer('version_id');
            $table->integer('class_id')->index('class_id');
            $table->integer('section_id')->index('section_id');
            $table->dateTime('admission_date')->nullable();
            $table->string('std_phone', 15);
            $table->string('std_phone1', 15);
            $table->string('std_fname', 100);
            $table->string('std_mname', 100);
            $table->dateTime('std_dob')->nullable();
            $table->string('std_gender', 8);
            $table->string('std_email', 100);
            $table->string('blood_group', 5);
            $table->string('std_present_address', 200);
            $table->string('std_permanent_address', 200);
            $table->string('std_f_occupation', 30);
            $table->string('std_m_occupation', 30);
            $table->string('std_gurdian_name', 100);
            $table->string('std_gurdian_relation', 30);
            $table->string('std_gurdian_mobile', 15);
            $table->string('std_gurdian_address', 200);
            $table->string('std_picture', 15);
            $table->string('std_category', 15);
            $table->integer('std_status');
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
        Schema::dropIfExists('students');
    }
}
