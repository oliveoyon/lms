<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inv_issue', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('issue_hash_id');
            $table->integer('issue_to');
            $table->dateTime('issue_date');
            $table->integer('product_id');
            $table->double('qty_issued');
            $table->string('requisition_no', 200);
            $table->integer('issue_by');
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
        Schema::dropIfExists('inv_issue');
    }
}
