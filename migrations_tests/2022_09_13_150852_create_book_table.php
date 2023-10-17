<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('book_hash_id');
            $table->integer('book_cat_id')->index('book_cat_id');
            $table->string('book_title', 200);
            $table->string('author', 500);
            $table->string('isbn', 100);
            $table->string('edition', 50);
            $table->string('publisher', 50);
            $table->string('shelf', 50);
            $table->string('position', 50);
            $table->dateTime('book_purchase_date');
            $table->double('cost');
            $table->integer('no_of_copy');
            $table->string('availability', 10);
            $table->string('language', 15);
            $table->integer('book_status');
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
        Schema::dropIfExists('book');
    }
}
