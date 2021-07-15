<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsPapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_papers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('writer_id')->unsigned()->default(0)->comment("users.id");
            $table->bigInteger('ac_id')->unsigned()->default(0)->index()->comment("academies.id");
            $table->bigInteger('cl_id')->unsigned()->default(0)->index()->comment("classes.id");
            $table->bigInteger('sg_id')->unsigned()->default(0)->index()->comment("school_grades.id");
            $table->bigInteger('hg_id')->unsigned()->default(0)->index()->comment("hakgi.id");
            $table->integer('year')->unsigned()->default(0)->index();
            $table->integer('week')->unsigned()->default(0)->index();
            $table->bigInteger('tf_id')->unsigned()->default(0)->index()->comment("test_forms.id");
            $table->char('sp_code',10)->index()->default('A');
            $table->enum('sp_status',["SAVING","SENT","READY","ABLE"])->default("READY");
            $table->timestamp('sent_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_papers');
    }
}
