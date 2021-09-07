<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsSendResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_send_results', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('student_id')->unsigned()->default(0);
            $table->bigInteger('class_id')->unsigned()->default(0);
            $table->char('sms_paper_code',10)->index();
            $table->mediumText('sms_msg')->nullable();
            $table->enum('ssr_status',['READY','FALSE','SENT'])->default('READY');
            $table->string('sms_tel_no')->nullable();
            $table->enum('ssr_view',['Y','N'])->default('N');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_send_results');
    }
}
