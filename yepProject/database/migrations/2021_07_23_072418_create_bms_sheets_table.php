<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmsSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_sheets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("bs_title");
            $table->bigInteger("bs_id")->unsigned()->default(0);
            $table->bigInteger('ac_id')->unsigned()->default(0);
            $table->bigInteger('sg_id')->unsigned()->default(0);
            $table->bigInteger('us_id')->unsigned()->default(0);
            $table->bigInteger('pre_week')->unsigned()->default(0);
            $table->bigInteger('now_week')->unsigned()->default(0);
            $table->bigInteger('writer_id')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms_sheets');
    }
}
