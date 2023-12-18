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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('book_hash_id')->index();
            $table->unsignedBigInteger('book_cat_id')->constrained('book_categories')->onDelete('cascade');
            $table->string('book_title');
            $table->string('author');
            $table->string('isbn')->nullable();
            $table->string('edition')->nullable();
            $table->string('publisher')->nullable();
            $table->string('shelf')->nullable();
            $table->string('position')->nullable();
            $table->dateTime('book_purchase_date');
            $table->decimal('cost', 10, 2);
            $table->integer('no_of_copy');
            $table->string('availability', 15)->default('available');
            $table->string('language', 15);
            $table->integer('book_status')->default(1); // Assuming 1 for 'active', 0 for 'inactive'
            $table->integer('school_id');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
