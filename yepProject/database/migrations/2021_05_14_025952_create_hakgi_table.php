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
            $table->integer('year')->unsigned()->comment('year');
            $table->string('hakgi_name')->comment('name');
            $table->enum('show',["Y","N"])->default("Y")->comment('show or hidden');
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
