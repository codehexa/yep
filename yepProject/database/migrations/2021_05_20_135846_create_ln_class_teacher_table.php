<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLnClassTeacherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ln_class_teacher', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('class_id')->unsigned()->comment("classes.id");
            $table->bigInteger('teacher_id')->unsigned()->comment('users.id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ln_class_teacher');
    }
}
