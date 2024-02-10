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
        Schema::create('tr_assign_stds', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('tr_assign_hash_id');
            $table->integer('std_id');
            $table->unsignedBigInteger('route_id')->constrained('tr_routes')->onDelete('cascade');
            $table->integer('pickup_stopage');
            $table->integer('drop_stopage')->nullable();
            $table->time('pickup_time')->nullable();
            $table->time('drop_time')->nullable();
            $table->string('academic_year');
            $table->integer('tr_assign_status');
            $table->integer('school_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_assign_stds');
    }
};
