<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_vendor', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('vendor_hash_id');
            $table->string('vendor_name', 100);
            $table->string('vendor_address', 200);
            $table->string('vendor_phone', 50);
            $table->string('vendor_email', 100);
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
        Schema::dropIfExists('inv_vendor');
    }
}
