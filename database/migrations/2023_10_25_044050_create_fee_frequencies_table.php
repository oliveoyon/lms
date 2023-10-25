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
        Schema::create('fee_frequencies', function (Blueprint $table) {
            $table->id();
            $table->string('freq_hash_id');
            $table->string('freq_name');
            $table->integer('no_of_installment');
            $table->string('installment_period', 30)->nullable();
            $table->integer('freq_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_frequencies');
    }
};
