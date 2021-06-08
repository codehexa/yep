<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;

    protected $table = "students";
    protected $fillable = [
        "student_name","student_tel","student_hp",
        "parent_hp",
        "school_name","school_grade",
        "abs_id","class_id","teacher_name"
    ];
}
