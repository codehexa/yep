<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smsworks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger("ac_id")->unsigned()->default(0)->comment("academies.id");
            $table->integer("year")->unsigned()->default(0);
            $table->bigInteger("hakgi_id")->unsigned()->default(0)->comment("hakgi.id");
            $table->bigInteger("tw_id")->unsigned()->default(0)->comment("testweeks.id");
            $table->bigInteger("lv_id")->unsigned()->default(0)->comment("levels.id");
            $table->bigInteger("st_id")->unsigned()->default(0)->comment("students.id");
            $table->mediumText("tc_opinion")->nullable()->comment("선생님이 입력한 멘트");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('smsworks');
    }
}
