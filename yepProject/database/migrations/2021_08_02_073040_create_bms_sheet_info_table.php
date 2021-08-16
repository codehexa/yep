<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmsSheetInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_sheet_info', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('sheet_id')->unsigned()->default(0);
            $table->bigInteger('bsi_acid')->unsigned()->default(0)->comment('academy.id');
            $table->bigInteger('bsi_sgid')->unsigned()->default(0)->comment('academy.id');
            $table->bigInteger('bsi_hakgi')->unsigned()->default(0)->comment('academy.id');
            $table->bigInteger('bsi_usid')->unsigned()->default(0)->comment('academy.id');
            $table->text('bsi_comment')->nullable();
            $table->bigInteger('bsi_std_type')->unsigned()->default(0);
            $table->bigInteger('bsi_workbook')->unsigned()->nullable()->default(0);
            $table->enum('bsi_workbook_use',['Y','N'])->default('N')->nullable()->comment("workbook use check");
            $table->bigInteger('bsi_studybook')->unsigned()->default(0)->nullable()->comment('bms_study_books.id');
            $table->enum('bsi_studybook_use',['Y','N'])->default('N')->nullable()->comment("studybook use check");
            $table->bigInteger('bsi_dt')->unsigned()->default(0)->nullable()->comment('bms_dt.id');
            $table->enum('bsi_dt_use',['Y','N'])->default('N')->nullable()->comment("dt use check");
            $table->bigInteger('bsi_cls_id')->unsigned()->default(0);
            $table->bigInteger('bsi_curri_id')->unsigned()->default(0);
            $table->bigInteger('bsi_days')->unsigned()->default(0);
            $table->bigInteger('bsi_std_times')->unsigned()->default(0);
            $table->bigInteger('bsi_sdl')->unsigned()->nullable()->default(0)->comment("bms_sdl.id");
            $table->enum('bsi_sdl_use',['Y','N'])->default('N')->nullable()->comment("sdl use check");
            $table->integer('bsi_subjects_count')->unsigned()->nullable()->default(0);
            $table->bigInteger('bsi_pre_subject_1')->unsigned()->default(0);
            $table->bigInteger('bsi_pre_subject_2')->unsigned()->default(0);
            $table->bigInteger('writer_id')->unsigned()->default(0);
            $table->enum('bsi_deleted',['Y','N'])->default('N');
            $table->enum('bsi_status',['READY','SENDING','SENT'])->default('READY');
            $table->timestamp('bsi_sent_date')->nullable();
            $table->integer('bsi_sheet_index')->unsigned()->default(0);
            $table->bigInteger('bsi_pre_week')->unsigned()->default(0);
            $table->bigInteger('bsi_now_week')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms_sheet_info');
    }
}
