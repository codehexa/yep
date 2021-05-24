<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsscoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smsscores', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger("sw_id")->unsigned()->default(0)->comment("smsworks.id");
            $table->bigInteger("ta_id")->unsigned()->default(0)->comment("test_areas.id");
            $table->integer("score")->unsigned()->default(0)->comment("student's score");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('smsscores');
    }
}
