<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    use HasFactory;

    protected $table = "subjects";
    protected $fillable = [
        "sj_title","sj_max_score","sj_desc",
        "sg_id","parent_id","depth","has_child","sj_order"
    ];

    public function SchoolGrade(){
        return $this->belongsTo(schoolGrades::class, "sg_id");
    }
}
