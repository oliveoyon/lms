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
        Schema::create('academic_fee_heads', function (Blueprint $table) {
            $table->id();
            $table->string('aca_feehead_hash_id');
            $table->string('aca_feehead_name');
            $table->string('aca_feehead_description');
            $table->unsignedBigInteger('aca_feehead_freq')->constrained('fee_frequencies')->onDelete('cascade');
            $table->integer('no_of_installment');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_fee_heads');
    }
};
