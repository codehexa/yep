<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsSheetInfoItemsFunctions extends Model
{
    use HasFactory;

    protected $table = "bms_sheet_info_items_functions";
    protected $fillable = [
        "bs_subject_id","class_context","dt_between","book_work","out_work_first","out_work_second"
    ];
}
