<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHakgiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hakgi', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('hakgi_name')->comment('name');
            $table->bigInteger('school_grade')->unsigned()->default(0)->comment('school_grades.id');
            $table->enum('show',["Y","N"])->default("Y")->comment('show or hidden');
            $table->integer('weeks')->unsigned()->default(0)->comment('set max weeks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hakgi');
    }
}
