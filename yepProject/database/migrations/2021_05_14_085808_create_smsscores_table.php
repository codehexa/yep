<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsscoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smsscores', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger("sg_id")->unsigned()->index()->default(0)->comment("school_grades.id");
            $table->bigInteger("writer_id")->unsigned()->index()->default(0)->comment('users.id');
            $table->integer("year")->unsigned()->default(0)->comment("year");
            $table->integer("week")->unsigned()->default(0)->comment("week");
            $table->bigInteger("tf_id")->unsigned()->index()->default(0)->comment("test_forms.id");
            $table->bigInteger("st_id")->unsigned()->index()->default(0)->comment("students.id");
            $table->bigInteger("cl_id")->unsigned()->index()->default(0)->comment("classes.id");
            $table->bigInteger("hg_id")->unsigned()->index()->default(0)->comment("hakgi.id");
            $table->integer("score_count")->unsigned()->default(0)->comment("include Total item");
            $table->integer("score_0")->unsigned()->default(0);
            $table->integer("score_1")->unsigned()->default(0);
            $table->integer("score_2")->unsigned()->default(0);
            $table->integer("score_3")->unsigned()->default(0);
            $table->integer("score_4")->unsigned()->default(0);
            $table->integer("score_5")->unsigned()->default(0);
            $table->integer("score_6")->unsigned()->default(0);
            $table->integer("score_7")->unsigned()->default(0);
            $table->integer("score_8")->unsigned()->default(0);
            $table->integer("score_9")->unsigned()->default(0);
            $table->integer("score_10")->unsigned()->default(0);
            $table->integer("score_11")->unsigned()->default(0);
            $table->integer("score_12")->unsigned()->default(0);
            $table->integer("score_13")->unsigned()->default(0);
            $table->integer("score_14")->unsigned()->default(0);
            $table->integer("score_15")->unsigned()->default(0);
            $table->integer("score_16")->unsigned()->default(0);
            $table->integer("score_17")->unsigned()->default(0);
            $table->integer("score_18")->unsigned()->default(0);
            $table->integer("score_19")->unsigned()->default(0);
            $table->integer("score_20")->unsigned()->default(0);
            $table->integer("score_21")->unsigned()->default(0);
            $table->integer("score_22")->unsigned()->default(0);
            $table->integer("score_23")->unsigned()->default(0);
            $table->integer("score_24")->unsigned()->default(0);
            $table->integer("score_25")->unsigned()->default(0);
            $table->integer("score_26")->unsigned()->default(0);
            $table->integer("score_27")->unsigned()->default(0);
            $table->integer("score_28")->unsigned()->default(0);
            $table->integer("score_29")->unsigned()->default(0);
            $table->text("opinion")->nullable();
            $table->text("wordian")->nullable();
            $table->enum("sent",["Y","N"])->default("N")->nullable();
            $table->timestamp("sent_date")->nullable();
            $table->enum("saved_check",["Y","N"])->default("N");
            $table->enum("send_ready",["Y","N"])->default("Y")->comment("보내도 되는 지 확인");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('smsscores');
    }
}
