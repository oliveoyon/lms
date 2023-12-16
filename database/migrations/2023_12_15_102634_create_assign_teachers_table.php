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
        Schema::create('assign_teachers', function (Blueprint $table) {
            $table->id();
            $table->string('assign_hash_id')->unique();
            $table->foreignId('version_id')->constrained('edu_versions')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('edu_classes')->onDelete('cascade');
            $table->foreignId('section_id')->constrained('sections')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->string('academic_year', 4);
            $table->integer('status');
            $table->integer('school_id');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assign_teachers');
    }
};
