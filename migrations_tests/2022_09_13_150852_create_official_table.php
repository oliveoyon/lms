<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('official', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('official_hash_id');
            $table->string('name', 100);
            $table->string('phone', 15);
            $table->string('password', 300);
            $table->string('official_type', 100);
            $table->dateTime('create_time');
            $table->integer('official_status');
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
        Schema::dropIfExists('official');
    }
}
