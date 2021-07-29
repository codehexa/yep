<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmsSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_subjects', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('sg_id')->unsigned()->default(0);
            $table->string('subject_title');
            $table->integer('subject_index')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms_subjects');
    }
}
