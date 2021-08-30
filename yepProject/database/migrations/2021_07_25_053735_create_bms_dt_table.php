<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmsDtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_dt', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('dt_title');
            $table->string('dt_text')->nullable()->comment('실제 작성되는 텍스트');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms_dt');
    }
}
