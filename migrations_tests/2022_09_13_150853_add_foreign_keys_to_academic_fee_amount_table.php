<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAcademicFeeAmountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academic_fee_amount', function (Blueprint $table) {
            $table->foreign(['aca_group_id'], 'academic_fee_amount_ibfk_2')->references(['id'])->on('academic_fee_group');
            $table->foreign(['aca_feehead_id'], 'academic_fee_amount_ibfk_1')->references(['id'])->on('academic_fee_head');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('academic_fee_amount', function (Blueprint $table) {
            $table->dropForeign('academic_fee_amount_ibfk_2');
            $table->dropForeign('academic_fee_amount_ibfk_1');
        });
    }
}
