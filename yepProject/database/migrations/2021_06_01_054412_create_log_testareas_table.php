<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogTestareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_testareas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("mode")->nullable();
            $table->bigInteger("target_id")->unsigned()->default(0);
            $table->string("old_value")->nullable();
            $table->string("new_value")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_testareas');
    }
}
