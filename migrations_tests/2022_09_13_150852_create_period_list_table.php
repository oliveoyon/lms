<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('period_list', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('plist_hash_id');
            $table->string('academic_year', 10);
            $table->integer('version_id');
            $table->integer('class_id');
            $table->integer('section_id');
            $table->string('period_name', 12);
            $table->string('start_time', 20);
            $table->string('end_time', 20);
            $table->integer('plist_status');
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
        Schema::dropIfExists('period_list');
    }
}
