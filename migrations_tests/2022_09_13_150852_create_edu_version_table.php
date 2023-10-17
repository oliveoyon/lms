<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEduVersionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_version', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('version_hash_id');
            $table->string('version_name', 50);
            $table->integer('version_status');
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
        Schema::dropIfExists('edu_version');
    }
}
