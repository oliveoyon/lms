<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section', function (Blueprint $table) {
            $table->foreign(['class_id'], 'section_ibfk_1')->references(['id'])->on('class');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section', function (Blueprint $table) {
            $table->dropForeign('section_ibfk_1');
        });
    }
}
