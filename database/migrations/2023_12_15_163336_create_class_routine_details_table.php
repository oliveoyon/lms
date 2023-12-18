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
            $table->unsignedBigInteger('class_routine_id')->index()->references('id')->on('class_routines');
            $table->string('day_of_week');
            $table->unsignedBigInteger('period_id')->index()->references('id')->on('periods');
            $table->unsignedBigInteger('subject_id')->nullable()->index()->references('id')->on('subjects');
            $table->timestamps();
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
