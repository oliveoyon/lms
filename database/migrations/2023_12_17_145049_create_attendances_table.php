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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('attendance_hash_id')->unique(); // Unique identifier for attendance records
            $table->string('std_id', 15);
            $table->unsignedBigInteger('class_id')->constrained('edu_classes');
            $table->unsignedBigInteger('section_id')->constrained('sections'); // Assuming you have a sections table
            $table->integer('roll_no');
            $table->integer('academic_year');
            $table->enum('attendance', ['Present', 'Absent', 'Late'])->default('Present');
            $table->date('attendance_date');
            $table->string('month', 15);
            $table->string('fine_clearance', 11)->nullable();
            $table->integer('school_id'); // Assuming you have a schools table
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
