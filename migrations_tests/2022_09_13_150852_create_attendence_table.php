<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendence', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('attendence_hash_id');
            $table->string('std_id', 15);
            $table->integer('class_id');
            $table->integer('section_id');
            $table->integer('roll_no');
            $table->integer('academic_year');
            $table->string('attendence', 10);
            $table->date('attendence_date');
            $table->string('month', 15);
            $table->string('fine_clearance', 11)->nullable();
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
        Schema::dropIfExists('attendence');
    }
}
