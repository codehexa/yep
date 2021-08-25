<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmsPageSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_page_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('field_name')->comment('첫번째 인사 말');
            $table->integer('field_index')->unsigned()->default(0)->comment('표시 순서');
            $table->text('field_function')->comment('표시 되는 내용 함수 ');
            $table->string('field_tag')->nullable()->comment('기준 스트링');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms_page_settings');
    }
}
