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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('event_hash_id');
            $table->string('event_title');
            $table->text('event_description');
            $table->unsignedBigInteger('event_type_id')->constrained('event_type')->onDelete('cascade');            $table->string('upload');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('url');
            $table->string('color', 15);
            $table->integer('event_status');
            $table->integer('school_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
