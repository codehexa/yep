<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmsBbsCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_bbs_comments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('us_id')->unsigned()->index()->default(0)->comment('users.id');
            $table->string('us_name')->comment('users.name');
            $table->bigInteger('bbs_id')->unsigned()->index()->default(0)->comment('bms_bbs.id');
            $table->integer('bc_index')->unsigned()->default(0)->comment('comment sort index');
            $table->mediumText('bc_comment')->nullable()->comment('comment context');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms_bbs_comments');
    }
}
