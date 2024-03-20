<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tr_routes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('route_hash_id');
            $table->string('route_name', 100);
            $table->string('route_description', 200)->nullable();
            $table->integer('vehicle_id');
            $table->string('stopage_id', 11);
            $table->string('pickup_time', 10)->nullable();
            $table->string('drop_time', 10)->nullable();
            $table->integer('route_status');
            $table->integer('school_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_routes');
    }
};
