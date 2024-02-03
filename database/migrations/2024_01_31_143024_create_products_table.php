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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_hash_id');
            $table->string('product_name', 200);
            $table->foreignId('category_id')->constrained('inv_categories');
            $table->foreignId('unit_id')->constrained('units');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->double('total_qty')->default(0);
            $table->string('size', 100)->nullable();
            $table->string('location', 11)->nullable();
            $table->double('min_reminder')->nullable();
            $table->string('remarks', 50)->nullable();
            $table->integer('is_returnable')->default(0);
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
