<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestFormsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_forms_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('tf_id')->unsigned()->default(0)->index()->comment('test_forms.id');
            $table->bigInteger('curri_id')->unsigned()->default(0)->comment('curriculum.id');
            $table->bigInteger('sj_id')->unsigned()->default(0)->comment('subjects.id');
            $table->integer('tf_index')->unsigned()->default(0)->comment('order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_forms_items');
    }
}
