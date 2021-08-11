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
            $table->bigInteger('sg_id')->unsigned()->default(0)->comment('school_grades.id');
            $table->string('field_name')->comment('첫번째 인사 말');
            $table->integer('field_index')->unsigned()->default(0)->comment('표시 순서');
            $table->text('field_function')->comment('표시 되는 내용 함수 ');
            $table->enum('field_type',['TXT','ARRAY_START','ARRAY_END','FUNCTION'])->default('TXT')->comment('반복 여부. ARRAY 반복 형');
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
