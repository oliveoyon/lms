<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvRecipientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_recipient', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('recipient_hash_id');
            $table->string('recipient_name', 200);
            $table->integer('personal_id');
            $table->date('expiry_date')->nullable();
            $table->integer('status');
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
        Schema::dropIfExists('inv_recipient');
    }
}
