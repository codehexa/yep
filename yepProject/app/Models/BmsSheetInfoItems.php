<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsSheetInfoItems extends Model
{
    use HasFactory;

    protected $table = "bms_sheet_info_items";
    protected $fillable = [
        "bms_sheet_id","bms_sheet_info_id","bms_shi_index",
        "bms_sii_first_class","bms_sii_first_teacher",
        "bms_sii_second_class","bms_sii_second_teacher",
        "bms_sii_dt"
    ];
}
