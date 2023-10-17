<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostelRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hostel_room', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('room_hash_id');
            $table->integer('hostel_id')->index('hostel_id');
            $table->string('room_name', 50);
            $table->string('room_type', 15);
            $table->integer('no_of_bed');
            $table->double('cost');
            $table->string('room_description', 100);
            $table->integer('room_status');
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
        Schema::dropIfExists('hostel_room');
    }
}
