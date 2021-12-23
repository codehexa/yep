<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogSmspapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_smspapers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('lsp_mode')->nullable();
            $table->bigInteger('lsp_writer_id')->unsigned()->index();
            $table->bigInteger('lsp_sms_paper_id')->unsigned();
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();
            $table->string('lsp_field')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_smspapers');
    }
}
