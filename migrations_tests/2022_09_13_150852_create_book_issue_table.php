<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_issue', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('book_issue_hash_id');
            $table->integer('book_id')->index('book_id');
            $table->integer('issue_to');
            $table->dateTime('issue_date');
            $table->dateTime('return_date');
            $table->integer('qty_issued');
            $table->integer('requisition_no');
            $table->integer('issue_by');
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
        Schema::dropIfExists('book_issue');
    }
}
