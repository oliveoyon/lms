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
        Schema::create('academic_students', function (Blueprint $table) {
            $table->id();
            $table->string('std_hash_id');
            $table->string('std_id', 15);
            $table->integer('version_id');
            $table->integer('class_id');
            $table->integer('section_id');
            $table->integer('roll_no');
            $table->string('academic_year', 4);
            $table->string('std_password', 300);
            $table->integer('st_aca_status');
            $table->integer('school_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_students');
    }
};
