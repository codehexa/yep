<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBmsSheetInfoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_sheet_info_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('bms_sheet_id')->unsigned()->default(0);
            $table->bigInteger('bms_sheet_info_id')->unsigned()->default(0);
            $table->integer('bms_shi_index')->unsigned()->default(0);
            $table->bigInteger('bms_sii_first_class')->unsigned()->default(0);
            $table->bigInteger('bms_sii_first_teacher')->unsigned()->default(0);
            $table->bigInteger('bms_sii_second_class')->unsigned()->default(0);
            $table->bigInteger('bms_sii_second_teacher')->unsigned()->default(0);
            $table->enum('bms_sii_dt',['Y','N'])->default("N");
            $table->text('bms_sii_dt_direct')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bms_sheet_info_items');
    }
}
