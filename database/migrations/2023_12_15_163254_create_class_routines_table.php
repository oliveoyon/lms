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
        Schema::create('class_routines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('version_id')->references('id')->on('versions');
            $table->unsignedBigInteger('academic_year');
            $table->unsignedBigInteger('class_id')->references('id')->on('edu_classes');
            $table->unsignedBigInteger('section_id')->references('id')->on('sections');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_routines');
    }
};
