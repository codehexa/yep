<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("class_name");
            $table->bigInteger("ac_id")->unsigned()->default(0);
            $table->enum("show",["Y","N"])->default("Y");
            //$table->bigInteger("homeschool_id")->unsigned()->nullable()->comment("담임 , users.id. 사용 안함. 링크로 사용함.");
            $table->string("class_desc")->nullable()->comment("반 정보 간단 설명.");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classes');
    }
}
