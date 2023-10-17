<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hostel', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('hostel_hash_id');
            $table->string('hostel_name', 50);
            $table->string('hostel_type', 15);
            $table->string('hostel_description', 100);
            $table->integer('hostel_status');
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
        Schema::dropIfExists('hostel');
    }
}
