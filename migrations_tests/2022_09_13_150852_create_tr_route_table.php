<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrRouteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_route', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('route_hash_id');
            $table->string('route_name', 100);
            $table->string('route_description', 200);
            $table->integer('vehicle_id');
            $table->string('stopage_id', 11);
            $table->string('stopage_name', 100);
            $table->string('pickup_time', 10);
            $table->string('drop_time', 10);
            $table->integer('route_status');
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
        Schema::dropIfExists('tr_route');
    }
}
