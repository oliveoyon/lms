<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBookIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('book_issue', function (Blueprint $table) {
            $table->foreign(['book_id'], 'book_issue_ibfk_1')->references(['id'])->on('book');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_issue', function (Blueprint $table) {
            $table->dropForeign('book_issue_ibfk_1');
        });
    }
}
