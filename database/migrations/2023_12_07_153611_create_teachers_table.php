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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('teacher_hash_id');
            $table->string('teacher_name', 100);
            $table->string('teacher_user_name', 50);
            $table->string('teacher_mobile', 20);
            $table->string('teacher_email', 100)->nullable();
            $table->string('teacher_designation', 100);
            $table->string('teacher_gender', 10);
            $table->string('teacher_password', 300);
            $table->string('teacher_image', 50)->nullable();
            $table->integer('teacher_status');
            $table->integer('school_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
