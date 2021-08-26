<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmsHworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_hworks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('hwork_sgid')->unsigned()->default(0)->comment('school_grades.id');
            $table->bigInteger('hwork_class')->unsigned()->default(0)->comment('고등부 수업과목');
            $table->string('hwork_content')->nullable()->comment('수업내용');
            $table->string('hwork_dt')->nullable()->comment('DT범위(1)');
            $table->string('hwork_book')->nullable()->comment('교재과제');
            $table->string('hwork_output_first')->nullable()->comment('제출과제(첫수업)');
            $table->string('hwork_output_second')->nullable()->comment('제출과제(두번째수업)');
            $table->string('hwork_opt1')->nullable()->comment('Option 1');
            $table->string('hwork_opt2')->nullable()->comment('Option 2');
            $table->string('hwork_opt3')->nullable()->comment('Option 3');
            $table->string('hwork_opt4')->nullable()->comment('Option 4');
            $table->string('hwork_opt5')->nullable()->comment('Option 5');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms_hworks');
    }
}
