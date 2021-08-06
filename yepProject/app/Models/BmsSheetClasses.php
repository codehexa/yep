<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsSheetClasses extends Model
{
    use HasFactory;

    protected $table = "bms_sheet_classes";
    protected $fillable = [
        "bsc_sheet_id","bsc_sheet_info_id",
        "bsc_class_1_subject","bsc_class_1_teacher",
        "bsc_class_2_subject","bsc_class_2_teacher",
        "bsc_dt","bsc_index"
    ];
}
