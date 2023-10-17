<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassPeriodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_period', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('period_hash_id');
            $table->integer('version_id');
            $table->integer('class_id');
            $table->integer('section_id');
            $table->integer('subject_id')->nullable();
            $table->string('academic_year', 4);
            $table->string('day', 15);
            $table->integer('plist_id');
            $table->string('start_time', 15);
            $table->string('end_time', 15);
            $table->integer('period_status');
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
        Schema::dropIfExists('class_period');
    }
}
