<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmsBbsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_bbs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('academy_id')->unsigned()->default(0)->comment('academies.id');
            $table->bigInteger('us_id')->unsigned()->index()->default(0)->comment('users.id');
            $table->string('us_name')->comment('users.name');
            $table->string('bbs_title')->comment('bbs title');
            $table->mediumText('bbs_content')->nullable()->comment('bbs content');
            $table->enum('bbs_type',['ALL','NORMAL'])->default('NORMAL')->comment('bbs type');
            $table->integer('bbs_hits')->unsigned()->default(0)->comment('bbs view hits');
            $table->integer('bbs_added_count')->unsigned()->default(0)->comment('bbs added comment count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms_bbs');
    }
}
