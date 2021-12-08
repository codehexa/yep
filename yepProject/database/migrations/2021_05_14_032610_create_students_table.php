<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("student_name");
            $table->string("student_tel")->nullable()->comment("집 전화번호");
            $table->string("student_hp")->nullable();
            $table->string("parent_hp")->nullable();
            $table->string("school_name")->nullable();
            $table->string("school_grade")->nullable()->comment("학년");
            $table->bigInteger("class_id")->index()->unsigned()->default(0)->comment("classes.id, 복수 클래스 등록이 가능함으로 해당 필드는 사용하지 않음. 다만, 링크로 연결할 것.");
            //$table->bigInteger("teacher_id")->unsigned()->nullable()->default(0)->comment("users.id, 복수 등록이 가능함으로 해당 필드는 사용하지 않음. 다만, 링크로 연결할 것.");
            $table->integer("abs_id")->unsigned()->unique()->comment("학원사랑에서 제공하는 고유 아이디");
            $table->string("teacher_name")->nullable();
            $table->bigInteger("ac_id")->unsigned()->default(0)->comment('academies.id');
            $table->enum("is_live",["Y","N"])->default("Y")->nullable()->comment("퇴원학생일 경우, N");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
