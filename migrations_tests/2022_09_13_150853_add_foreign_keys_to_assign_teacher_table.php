<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAssignTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assign_teacher', function (Blueprint $table) {
            $table->foreign(['teacher_id'], 'assign_teacher_ibfk_1')->references(['id'])->on('teacher');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assign_teacher', function (Blueprint $table) {
            $table->dropForeign('assign_teacher_ibfk_1');
        });
    }
}
