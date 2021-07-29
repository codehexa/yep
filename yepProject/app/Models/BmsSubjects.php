<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsSubjects extends Model
{
    use HasFactory;

    protected $table = "bms_subjects";
    protected $fillable = [
        "sg_id","subject_title","subject_index"
    ];

    public function SchoolGradeObj(){
        return $this->hasOne(schoolGrades::class, "id","sg_id");
    }
}
