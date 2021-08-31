<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmsWorkbooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_workbooks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('bw_title');
            $table->integer('bw_index')->unsigned()->default(0);
            $table->string('bw_text')->nullable()->comment('실제 교재과제에 포함되는 내용');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms_workbooks');
    }
}
