<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_students', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("ls_mode")->comment("add,modify,delete");
            $table->bigInteger("ls_writer_id")->unsigned()->default(0);
            $table->bigInteger('ls_target_id')->unsigned()->default(0)->comment("students.id");
            $table->string("old_value")->nullable();
            $table->string("new_value")->nullable();
            $table->string("ls_field")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_students');
    }
}
