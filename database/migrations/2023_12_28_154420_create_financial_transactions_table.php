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
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_hash_id')->unique();
            $table->string('trnx_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('transaction_type');
            $table->unsignedBigInteger('related_id');
            $table->enum('account_category', ['asset', 'liability', 'expense', 'equity', 'revenue'])->nullable();
            $table->double('amount');
            $table->timestamp('transaction_date');
            $table->text('description')->nullable();
            $table->timestamps();

            // Define foreign key constraints if needed
            // $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('related_id')->references('id')->on('related_table');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
