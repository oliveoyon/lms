<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrVehicleTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_vehicle_type', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('vehicle_type_hash_id');
            $table->string('vehicle_type', 50);
            $table->integer('vehicle_type_status');
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
        Schema::dropIfExists('tr_vehicle_type');
    }
}
