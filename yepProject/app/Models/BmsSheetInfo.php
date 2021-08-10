<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsSheetInfo extends Model
{
    use HasFactory;
    protected $table = "bms_sheet_info";
    protected $fillable = [
        "sheet_id","bsi_comment","bsi_std_type","bsi_workbook","bsi_cls_id","bsi_days","bsi_std_times",
        "bsi_subjects_count",
        "bsi_pre_subject_1","bsi_pre_subject_2",
        "writer_id","bsi_deleted","bsi_status","bsi_sent_date","bsi_sheet_index",
        "bsi_pre_week","bsi_now_week"
    ];

    public function nowDays(){
        return $this->hasOne(BmsDays::class,"id","bsi_days");
    }

    public function BmsStudyType(){
        return $this->hasOne(BmsStudyTypes::class,"id","bsi_std_type");
    }

    public function BmsWorkbook(){
        return $this->hasOne(BmsWorkbooks::class, "id","bsi_workbook");
    }

    public function BmsClass(){
        return $this->hasOne(Classes::class, "id","bsi_cls_id");
    }

    public function BmsDays(){
        return $this->hasOne(BmsDays::class, "id","bsi_days");
    }

    public function BmsStudyTimes(){
        return $this->hasOne(BmsStudyTimes::class, "id","bsi_std_times");
    }
}
