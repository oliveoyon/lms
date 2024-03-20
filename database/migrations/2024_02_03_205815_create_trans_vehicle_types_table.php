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
        Schema::create('trans_vehicle_types', function (Blueprint $table) {
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
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_vehicle_types');
    }
};
