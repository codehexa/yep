<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmsSheetInfoItemsFunctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_sheet_info_items_functions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('bs_subject_id')->unsigned()->default(0)->comment('bms_subjects.id');
            $table->bigInteger('scg_id')->unsigned()->default(0)->comment('school_grades.id');
            $table->string('class_context')->nullable()->comment('수업 내용');
            $table->string('dt_between')->nullable()->comment('DT 범위 1 ');
            $table->string('book_work')->nullable()->comment('교재 과제');
            $table->string('out_work_first')->nullable()->comment('제출과제 (첫수업)');
            $table->string('out_work_second')->nullable()->comment('제출과제 (두번째 수업');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms_sheet_info_items_functions');
    }
}
