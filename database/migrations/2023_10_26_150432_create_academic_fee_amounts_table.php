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
        Schema::create('academic_fee_amounts', function (Blueprint $table) {
            $table->id();
            $table->string('aca_feeamount_hash_id');
            $table->integer('aca_group_id')->index('aca_group_id');
            $table->integer('aca_feehead_id')->index('aca_feehead_id');
            $table->double('amount');
            $table->integer('class_id');
            $table->integer('academic_year');
            $table->integer('aca_feeamount_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_fee_amounts');
    }
};
