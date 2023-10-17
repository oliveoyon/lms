<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFineSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fine_settings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('fine_set_hash_id');
            $table->string('academic_year', 4);
            $table->integer('version_id');
            $table->integer('class_id');
            $table->string('fine_type', 20);
            $table->string('amount', 12);
            $table->integer('after_day')->nullable();
            $table->integer('fine_set_status');
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
        Schema::dropIfExists('fine_settings');
    }
}
