<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('applied_students', function (Blueprint $table) {
            $table->id();
            $table->string('std_hash_id');
            $table->string('std_name', 100);
            $table->string('std_name_bn', 200);
            $table->string('academic_year', 4);
            $table->integer('version_id');
            $table->integer('class_id')->index('class_id');
            $table->string('std_phone', 15);
            $table->string('std_phone1', 15)->nullable();
            $table->string('std_fname', 100);
            $table->string('std_mname', 100);
            $table->dateTime('std_dob')->nullable();
            $table->string('std_gender', 8);
            $table->string('std_email', 100)->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->string('std_present_address', 200);
            $table->string('std_permanent_address', 200);
            $table->string('std_f_occupation', 30)->nullable();
            $table->string('std_m_occupation', 30)->nullable();
            $table->double('f_yearly_income')->nullable();
            $table->string('std_gurdian_name', 100)->nullable();
            $table->string('std_gurdian_relation', 30)->nullable();
            $table->string('std_gurdian_mobile', 15)->nullable();
            $table->string('std_gurdian_address', 200)->nullable();
            $table->string('std_picture', 15)->nullable();
            $table->string('std_birth_reg', 25)->nullable();
            $table->integer('school_id');
            $table->integer('std_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applied_students');
    }
};
