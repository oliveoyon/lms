<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToHostelRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hostel_room', function (Blueprint $table) {
            $table->foreign(['hostel_id'], 'hostel_room_ibfk_1')->references(['id'])->on('hostel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hostel_room', function (Blueprint $table) {
            $table->dropForeign('hostel_room_ibfk_1');
        });
    }
}
