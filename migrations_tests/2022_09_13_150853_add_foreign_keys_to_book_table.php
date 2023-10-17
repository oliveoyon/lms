<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book', function (Blueprint $table) {
            $table->foreign(['book_cat_id'], 'book_ibfk_1')->references(['id'])->on('book_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book', function (Blueprint $table) {
            $table->dropForeign('book_ibfk_1');
        });
    }
}
