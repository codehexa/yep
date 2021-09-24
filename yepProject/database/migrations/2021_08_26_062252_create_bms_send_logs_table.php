<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmsSendLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_send_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->mediumText('tels')->nullable()->comment("parents.tel");
            $table->string('bsl_title')->comment('title');
            $table->dateTime('bsl_sent_date')->nullable()->comment('sent log date');
            $table->bigInteger('bsl_us_id')->unsigned()->default(0)->comment('auth.id');
            $table->string('bsl_us_name')->nullable()->comment('auth.name');
            $table->mediumText('bsl_fault_msg')->nullable()->comment('false tel ');
            $table->string('bsl_result_msg')->nullable()->comment('aligo 에서 제공하는 성공/실패');
            $table->string('bsl_usage_point')->nullable()->comment('aligo 차감 포인트 ');
            $table->string('bsl_aligo_result_code')->nullable()->comment('aligo result code');
            $table->mediumText('bsl_send_text')->nullable()->comment("보내는 내용");
            $table->enum('bsl_reservation_code',['Y','N'])->default('N')->comment('예약여부');
            $table->date('bsl_reservation_date')->nullable()->comment('예약일자');
            $table->string('bsl_reservation_time')->nullable()->comment('AMHHMM');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms_send_logs');
    }
}
