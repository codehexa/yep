<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmsStudyTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_study_types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("study_title");
            $table->integer("study_type_index")->unsigned()->default(0);
            $table->enum('show_zoom',['Y','N'])->default('N')->comment('zoom id 를 보여주기 위함.');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms_study_types');
    }
}
