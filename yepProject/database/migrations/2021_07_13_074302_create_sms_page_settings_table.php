<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsPageSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_page_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("greetings")->nullable();
            $table->string("result_link_url")->nullable();
            $table->string("blog_link_url")->nullable();
            $table->string("teacher_title")->nullable();
            $table->string("sps_opt_1")->nullable();
            $table->string("sps_opt_2")->nullable();
            $table->string("sps_opt_3")->nullable();
            $table->string("sps_opt_4")->nullable();
            $table->string("sps_opt_5")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_page_settings');
    }
}
