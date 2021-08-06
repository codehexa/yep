<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMmsStacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mms_stacks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('student_id')->unsigned()->default(0);
            $table->string('tel');
            $table->mediumText('mms_text')->nullable();
            $table->enum('status',['READY','SENDING','SENT','DROP'])->default('READY');
            $table->timestamp('sent_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mms_stacks');
    }
}
