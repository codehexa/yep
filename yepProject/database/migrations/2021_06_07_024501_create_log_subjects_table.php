<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_subjects', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("sj_mode")->nullable();
            $table->bigInteger("target_id")->unsigned()->default(0);
            $table->string("sj_old")->nullable();
            $table->string("sj_new")->nullable();
            $table->bigInteger("writer_id")->unsigned()->default(0);
            $table->string("log_field")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_subjects');
    }
}
