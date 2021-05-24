<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger("lv_id")->unsigned()->default(0)->comment("levels.id");
            $table->bigInteger("ta_id")->unsigned()->default(0)->comment("test_areas.id");
            $table->integer("min_score")->unsigned()->default(0)->comment("test minimum score");
            $table->integer("max_score")->unsigned()->default(0)->comment("test maximum score");
            $table->bigInteger("writer_id")->unsigned()->default(0)->comment("users.id");
            $table->mediumText("opinion")->nullable()->comment("opinion text");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
