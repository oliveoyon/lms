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
        Schema::create('book_issues', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->unsignedBigInteger('book_id')->constrained('books')->onDelete('cascade');
            $table->unsignedInteger('quantity')->default(1); // Number of copies issued
            $table->dateTime('issue_date');
            $table->dateTime('due_date');
            $table->dateTime('return_date')->nullable();
            $table->decimal('fine_amount', 10, 2)->default(0.00);
            $table->string('issue_status', 20)->default('issued'); // 'issued', 'returned', 'overdue'
            $table->timestamps();
            
            // Indexes
            $table->index(['student_id', 'book_id']);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_issues');
    }
};
