<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmsSdlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_sdl', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('bs_title');
            $table->integer('bs_index')->unsigned()->default(0);
            $table->enum('bs_direct',['Y','N'])->default('N')->comment('직접작성 일 경우, Y');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms_sdl');
    }
}
