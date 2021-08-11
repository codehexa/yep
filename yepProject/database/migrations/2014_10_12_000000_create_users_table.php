<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('uid')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->enum('power',['ADMIN','MANAGER','TEACHER'])->default('TEACHER');
            $table->enum('live',['Y','N'])->default('N');
            $table->timestamp('drop_date')->nullable();
            $table->bigInteger('academy_id')->unsigned()->nullable()->default(0)->comment('academies.id');
            $table->timestamp('last_login')->nullable();
            $table->string('zoom_id')->nullable()->comment('zoom id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
