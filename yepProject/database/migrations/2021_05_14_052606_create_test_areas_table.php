<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_areas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger("ta_school_grade")->unsigned()->default('0')->comment("school_grades.id");
            $table->string("ta_name");
            $table->bigInteger("parent_id")->unsigned()->default(0)->comment("상위 그룹 인덱스 번호");
            $table->string("ta_code")->nullable();
            $table->integer("ta_child_count")->unsigned()->default(0)->comment("하위 그룹 노드 갯수");
            $table->integer("ta_max_score")->unsigned()->default(0)->comment("시험 최대 점수");
            $table->integer("ta_depth")->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_areas');
    }
}
