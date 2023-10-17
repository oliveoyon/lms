<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentSubmissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_submission', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('submission_hash_id');
            $table->string('std_id', 19);
            $table->integer('assignment_id');
            $table->string('detail', 100);
            $table->string('upload', 300);
            $table->integer('assigned_status');
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
        Schema::dropIfExists('assignment_submission');
    }
}
