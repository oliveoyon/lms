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
        Schema::create('fee_collections', function (Blueprint $table) {
            $table->id();
            $table->string('fee_collection_hash_id');
            $table->unsignedBigInteger('std_id')->constrained('students')->onDelete('cascade');
            $table->unsignedBigInteger('fee_group_id')->constrained('academic_fee_groups')->onDelete('cascade');
            $table->unsignedBigInteger('aca_feehead_id')->constrained('academic_fee_heads')->onDelete('cascade');
            $table->unsignedBigInteger('aca_feeamount_id')->constrained('academic_fee_amounts')->onDelete('cascade');
            $table->double('payable_amount');
            $table->double('amount_paid')->default(0);
            $table->boolean('is_paid')->default(false);
            $table->dateTime('paid_date')->nullable();
            $table->date('due_date');
            $table->text('fee_description')->nullable(); // Added fee_description column
            $table->timestamps();
            $table->integer('academic_year');
            $table->integer('fee_collection_status');
            $table->integer('school_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_collections');
    }
};
