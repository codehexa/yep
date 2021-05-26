<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned()->comment('writer id. users.id');
            $table->bigInteger('target_id')->unsigned()->comment("target id. users.id");
            $table->string('field_name')->nullable()->comment('edit field name');
            $table->string('old_value')->nullable()->comment('old value');
            $table->string('new_value')->nullable()->comment('new value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_users');
    }
}
