<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('notification_hash_id');
            $table->string('notiifcation_type', 50);
            $table->string('notiifcation_icon', 50);
            $table->integer('sender_id')->nullable();
            $table->integer('receiver_id')->nullable();
            $table->string('user_type', 50);
            $table->string('notification_title', 50);
            $table->string('description', 100);
            $table->string('url', 200);
            $table->integer('is_read');
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
        Schema::dropIfExists('notification');
    }
}
