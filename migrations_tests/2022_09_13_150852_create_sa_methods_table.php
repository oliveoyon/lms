<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sa_methods', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('controller_id');
            $table->string('controller_name', 100);
            $table->string('method_name', 50);
            $table->integer('school_id');
            $table->timestamps();
            $table->string('user', 20);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sa_methods');
    }
}
