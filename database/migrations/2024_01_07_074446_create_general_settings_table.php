<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('set_hash_id');
            $table->string('school_title');
            $table->string('school_title_bn');
            $table->string('school_short_name');
            $table->string('school_code')->nullable();
            $table->string('school_eiin_no')->nullable();
            $table->string('school_email');
            $table->string('school_phone');
            $table->string('school_phone1')->nullable();
            $table->string('school_phone2')->nullable();
            $table->string('school_fax')->nullable();
            $table->text('school_address');
            $table->string('school_country');
            $table->string('currency_sign');
            $table->string('currency_name');
            $table->string('school_geocode')->nullable();
            $table->string('school_facebook')->nullable();
            $table->string('school_twitter')->nullable();
            $table->string('school_google')->nullable();
            $table->string('school_linkedin')->nullable();
            $table->string('school_youtube')->nullable();
            $table->string('school_copyrights');
            $table->string('school_logo');
            $table->string('currency')->nullable();
            $table->integer('set_status')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('language')->nullable()->default('en');
            $table->boolean('enable_notifications')->default(true);
            $table->integer('school_id')->nullable();
            $table->timestamps();
        });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
