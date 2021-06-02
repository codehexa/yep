<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLnCurriSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ln_curri_subject', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger("curri_id")->unsigned()->default(0)->index();
            $table->bigInteger("subject_id")->unsigned()->default(0)->index();
            $table->bigInteger("grade_id")->unsigned()->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ln_curri_subject');
    }
}
