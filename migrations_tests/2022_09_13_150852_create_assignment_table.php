<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('assignment_hash_id');
            $table->integer('version_id');
            $table->integer('class_id');
            $table->string('section_id', 10);
            $table->string('academic_year', 10);
            $table->string('assignment_type', 50);
            $table->string('assignment_title', 50);
            $table->text('assignment_description');
            $table->string('assignment_img', 300);
            $table->integer('assignment_status');
            $table->dateTime('submission_date')->nullable();
            $table->string('uploaded_by', 11);
            $table->integer('for_all')->default(0);
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
        Schema::dropIfExists('assignment');
    }
}
