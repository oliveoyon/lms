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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_hash_id');
            $table->integer('version_id');
            $table->unsignedBigInteger('class_id')->constrained('edu_classess')->onDelete('cascade');            $table->integer('class_teacher_id')->nullable();
            $table->string('section_name', 100);
            $table->integer('max_students');
            $table->integer('section_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
