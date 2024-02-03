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
        Schema::create('trans_stopages', function (Blueprint $table) {
            $table->id();
            $table->string('stopage_hash_id');
            $table->string('stopage_name');
            $table->string('stopage_type');
            $table->string('distance')->nullable();
            $table->string('stopage_description')->nullable();
            $table->integer('stopage_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_stopages');
    }
};
