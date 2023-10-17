<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('user_hash_id');
            $table->string('user_name', 11);
            $table->string('email', 100);
            $table->string('password', 200);
            $table->string('permission', 500);
            $table->integer('def');
            $table->integer('gdg');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
