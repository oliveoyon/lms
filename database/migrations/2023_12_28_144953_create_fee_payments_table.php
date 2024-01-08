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
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fee_collection_id');
            $table->double('amount_paid');
            $table->dateTime('payment_date');
            $table->string('payment_method')->nullable();
            $table->string('trnx_id')->nullable();
            $table->unsignedBigInteger('status');
            $table->unsignedBigInteger('school_id');
            $table->timestamps();

            $table->foreign('fee_collection_id')->references('id')->on('fee_collections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};
