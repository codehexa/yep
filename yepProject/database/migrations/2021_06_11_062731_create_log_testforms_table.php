<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogTestformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_testforms', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("lt_mode")->nullable();
            $table->bigInteger("lt_id")->unsigned()->default(0)->comment('test_forms.id');
            $table->string("lt_old")->nullable();
            $table->string("lt_new")->nullable();
            $table->bigInteger("writer_id")->unsigned()->default(0)->comment("users.id");
            $table->string("lt_field")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_testforms');
    }
}
