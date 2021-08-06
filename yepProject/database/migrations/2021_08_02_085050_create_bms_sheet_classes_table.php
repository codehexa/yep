<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmsSheetClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_sheet_classes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('bsc_sheet_id')->unsigned()->default(0)->index();
            $table->bigInteger('bsc_sheet_info_id')->unsigned()->default(0)->index();
            $table->bigInteger('bsc_class_1_subject')->unsigned()->default(0);
            $table->bigInteger('bsc_class_1_teacher')->unsigned()->default(0);
            $table->bigInteger('bsc_class_2_subject')->unsigned()->default(0);
            $table->bigInteger('bsc_class_2_teacher')->unsigned()->default(0);
            $table->enum('bsc_dt',['Y','N'])->default('N');
            $table->integer('bsc_index')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms_sheet_classes');
    }
}
