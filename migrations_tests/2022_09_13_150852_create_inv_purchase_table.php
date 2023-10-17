<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_purchase', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('purchase_hash_id');
            $table->integer('product_id');
            $table->integer('category_id');
            $table->integer('unit_id');
            $table->double('unit_rate');
            $table->double('quantity');
            $table->dateTime('date_purchased');
            $table->string('invoice_no', 200);
            $table->string('purchase_requisition_no', 200);
            $table->integer('shop_name');
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
        Schema::dropIfExists('inv_purchase');
    }
}
