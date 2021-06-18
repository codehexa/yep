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
            $table->bigInteger('sj_id')->unsigned()->default(0)->index()->comment('subjects.id');
            $table->integer('sj_index')->unsigned()->default(0)->comment('order');
            $table->string('sj_title');
            $table->enum('sj_type',['N','T'])->default('N')->comment('N - Normal, T - Total');
            $table->integer('sj_max_score')->unsigned()->default(0)->comment('Subject max score');
            $table->bigInteger('sj_parent_id')->unsigned()->default(0);
            $table->integer('sj_depth')->unsigned()->default(0);
            $table->enum('sj_has_child',["Y","N"])->default("N");
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
