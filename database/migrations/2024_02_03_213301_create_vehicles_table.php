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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('vehicle_hash_id');
            $table->string('vehicle_name', 50);
            $table->string('vehicle_no', 20);
            $table->string('vehicle_reg_no', 25);
            $table->integer('vehicle_type_id');
            $table->integer('vehicle_status');
            $table->integer('school_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
