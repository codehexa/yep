<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_forms', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('writer_id')->unsigned()->default(0)->index()->comment('Users.id');
            $table->string('form_title')->comment('form title');
            $table->bigInteger('ac_id')->unsigned()->default(0)->index()->comment('academy.id');
            $table->bigInteger('grade_id')->unsigned()->default(0)->index()->comment('school_grades.id');
            $table->bigInteger('class_id')->unsigned()->default(0)->index()->comment('classes.id');
            $table->integer('subjects_count')->unsigned()->default(0)->comment('subject children count');
            $table->string('tf_desc')->nullable()->comment('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_forms');
    }
}
