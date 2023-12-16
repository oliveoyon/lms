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
        Schema::create('class_routine_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_routine_id');
            $table->string('day_of_week');
            $table->unsignedBigInteger('period_id');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->timestamps();
    
            // Add foreign key constraints if needed
            $table->foreign('class_routine_id')->references('id')->on('class_routines');
            $table->foreign('period_id')->references('id')->on('periods');
            // $table->foreign('subject_id')->references('id')->on('subjects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_routine_details');
    }
};
