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
        Schema::create('edu_classes', function (Blueprint $table) {
            $table->id();
            $table->string('class_hash_id');
            $table->foreignId('version_id')->constrained('edu_versions')->onDelete('cascade');
            $table->string('class_name', 50);
            $table->integer('class_numeric');
            $table->integer('class_status');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edu_classes');
    }
};
