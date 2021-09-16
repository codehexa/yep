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
            $table->string("blog_guide")->nullable()->comment('blog guide text');
            $table->string("teacher_title")->nullable();
            $table->string("sps_opt_1")->nullable()->comment('학생이름 성적표 링크');
            $table->string("sps_opt_2")->nullable()->comment('페이지 하단 내용');
            $table->string("sps_opt_3")->nullable();
            $table->string("sps_opt_4")->nullable();
            $table->string("sps_opt_5")->nullable();
            $table->enum("use_top",["Y","N"])->default("N")->comment("상위 타이틀 표시 여부 ");
            $table->enum("use_bottom",["Y","N"])->default("N")->comment("하위 타이틀 표시 여부 ");
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
