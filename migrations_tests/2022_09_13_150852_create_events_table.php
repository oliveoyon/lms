<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('event_hash_id');
            $table->string('event_title', 200);
            $table->text('event_description');
            $table->string('event_type', 10);
            $table->string('upload', 50);
            $table->string('start_date', 50);
            $table->string('end_date', 200);
            $table->string('url', 200);
            $table->string('color', 15);
            $table->integer('event_status');
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
        Schema::dropIfExists('events');
    }
}
