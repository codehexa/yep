<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * curriculums table needless
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("sj_title");
            $table->integer("sj_max_score");
            $table->string("sj_desc")->nullable();
            $table->bigInteger("sg_id")->unsigned()->default(0)->comment("school_grades.id");
            $table->bigInteger('parent_id')->unsigned()->default(0);
            $table->integer('depth')->unsigned()->default(0);
            $table->enum('has_child',['Y','N'])->default('N');
            $table->integer('sj_order')->unsigned()->default(0);
            $table->enum('sj_type',['N','T'])->default('N');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subjects');
    }
}
