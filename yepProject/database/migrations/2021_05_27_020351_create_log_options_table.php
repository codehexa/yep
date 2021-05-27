<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_options', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned()->default(0)->comment('users.id');
            $table->string('opt_code')->comment('option settings.set_code');
            $table->string('opt_old_value')->nullable()->comment('old settings.set_value');
            $table->string('opt_new_value')->nullable()->comment('new settings.set_value');
            $table->string('opt_log_desc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_options');
    }
}
