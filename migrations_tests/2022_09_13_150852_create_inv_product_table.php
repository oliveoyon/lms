<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_product', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('product_hash_id');
            $table->string('product_name', 200);
            $table->integer('category_id');
            $table->integer('unit_id');
            $table->double('total_qty')->default(0);
            $table->string('size', 100);
            $table->string('location', 11)->nullable();
            $table->integer('min_reminder');
            $table->string('remarks', 50);
            $table->integer('status');
            $table->integer('school_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inv_product');
    }
}
