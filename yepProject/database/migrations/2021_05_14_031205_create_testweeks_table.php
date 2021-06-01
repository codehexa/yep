<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestweeksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testweeks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("year")->unsigned()->comment("year");
            $table->integer("weeks")->unsigned()->comment("weeks numbering");
            $table->mediumText("context")->nullable()->comment("comments");
            $table->enum("show",["Y","N"])->default("Y")->comment("show or hide");
            $table->bigInteger("hakgi")->unsigned()->default(0)->comment("hakgi.id");
            $table->bigInteger("school_grade")->unsigned()->default(0)->comment("school_grades.id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('testweeks');
    }
}
