<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestForms extends Model
{
    use HasFactory;

    protected $table = "test_forms";
    protected $fillable = [
        "writer_id","form_title","ac_id","grade_id","class_id","subjects_count",
        "tf_desc",
        "sj_0","sj_1","sj_2","sj_3","sj_4","sj_5","sj_6","sj_7","sj_8","sj_9",
        "sj_10","sj_11","sj_12","sj_13","sj_14","sj_15","sj_16","sj_17","sj_18","sj_19"
    ];

    public function Grades(){
        return $this->belongsTo(schoolGrades::class,"grade_id");
    }

    public function Writer(){
        return $this->belongsTo(User::class, "writer_id");
    }

    public function Academy(){
        return $this->belongsTo(Academies::class,"ac_id");
    }

    public function ClassObj(){
        return $this->belongsTo(Classes::class, "class_id");
    }
}
