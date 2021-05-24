<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogAcademiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_academies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger("user_id")->unsigned()->default(0)->index()->comment("users.id");
            $table->string("log_category")->default('')->comment('category name');
            $table->enum('log_mode',['add','modify','delete'])->default('add')->comment('do mode');
            $table->bigInteger("target_id")->unsigned()->nullable()->default('0')->comment('do target id');
            $table->string('log_desc')->nullable()->comment("do comments");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_academies');
    }
}
