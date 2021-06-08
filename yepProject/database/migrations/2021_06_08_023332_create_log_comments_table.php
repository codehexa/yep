<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_comments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("cm_mode")->nullable();
            $table->bigInteger("target_id")->unsigned()->default(0);
            $table->string("cm_old")->nullable();
            $table->string("cm_new")->nullable();
            $table->bigInteger("writer_id")->unsigned()->default(0);
            $table->string("cm_field")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_comments');
    }
}
