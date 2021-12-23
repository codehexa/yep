<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_scores', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("ss_mode")->nullable();
            $table->bigInteger("ss_writer_id")->unsigned()->default('0');
            $table->bigInteger("ss_target_id")->unsigned()->default('0');
            $table->string("old_value")->nullable();
            $table->string("new_value")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_scores');
    }
}
