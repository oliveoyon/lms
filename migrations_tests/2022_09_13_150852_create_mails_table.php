<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mails', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('message_hash_id');
            $table->text('message');
            $table->string('file', 200)->nullable();
            $table->dateTime('mailingdate')->nullable();
            $table->unsignedInteger('starred_status')->default(0);
            $table->string('sender_id', 200)->default('');
            $table->string('reciever_id', 200)->default('');
            $table->unsignedInteger('inbox_status')->default(0);
            $table->unsignedInteger('sent_status')->default(0);
            $table->unsignedInteger('draft_status')->default(0);
            $table->unsignedInteger('trash_status')->default(0);
            $table->string('subject', 200)->nullable();
            $table->unsignedInteger('read_status')->default(0);
            $table->unsignedInteger('delete_status')->default(0);
            $table->text('secret');
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
        Schema::dropIfExists('mails');
    }
}
