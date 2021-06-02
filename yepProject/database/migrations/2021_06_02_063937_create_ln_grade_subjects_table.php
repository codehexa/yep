<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLnGradeSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ln_grade_subjects', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger("grade_id")->unsigned()->default(0)->comment("school_grades.id");
            $table->bigInteger("subject_id")->unsigned()->default(0)->comment("subjects.id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ln_grade_subjects');
    }
}
