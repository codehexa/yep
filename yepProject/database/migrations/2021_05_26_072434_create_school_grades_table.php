<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_grades', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("scg_name")->comment("초/중/고/대/학교");
            $table->integer('scg_index')->unsigned()->default(0);
            $table->enum("scg_not_set",["Y","N"])->default("N")->comment("미지정 일 경우, Y");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_grades');
    }
}
