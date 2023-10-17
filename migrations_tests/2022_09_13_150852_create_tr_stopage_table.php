<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrStopageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_stopage', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('stopage_hash_id');
            $table->string('stopage_name', 50);
            $table->string('stopage_type', 15);
            $table->string('distance', 20);
            $table->string('stopage_description', 100);
            $table->integer('stopage_status');
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
        Schema::dropIfExists('tr_stopage');
    }
}
