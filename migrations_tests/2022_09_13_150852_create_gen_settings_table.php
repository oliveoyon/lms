<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gen_settings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('set_hash_id');
            $table->string('school_title', 200);
            $table->string('school_title_bn', 100);
            $table->string('school_short_name', 15);
            $table->string('school_code', 100);
            $table->string('school_reg_no', 100);
            $table->string('school_email', 100);
            $table->string('school_phone', 15);
            $table->string('school_phone1', 15);
            $table->string('school_phone2', 15);
            $table->string('school_fax', 20);
            $table->text('school_address');
            $table->string('school_country', 25);
            $table->string('currency_sign', 1);
            $table->string('currency_name', 10);
            $table->string('school_geocode', 100);
            $table->string('school_facebook', 100);
            $table->string('school_twitter', 100);
            $table->string('school_google', 100);
            $table->string('school_linkedin', 100);
            $table->string('school_youtube', 100);
            $table->string('school_copyrights', 100);
            $table->string('school_logo', 15);
            $table->string('currency', 30);
            $table->integer('set_status');
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
        Schema::dropIfExists('gen_settings');
    }
}
