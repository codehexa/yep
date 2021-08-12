<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmsStudyBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_study_books', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("bsb_title");
            $table->integer("bsb_index")->unsigned()->default(0)->comment("순서 ");
            $table->enum("bsb_direct",["Y","N"])->default("N")->comment("직접작성일 경우 선택. Y");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms_study_books');
    }
}
