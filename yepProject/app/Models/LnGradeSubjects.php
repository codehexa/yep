<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LnGradeSubjects extends Model
{
    use HasFactory;

    protected $table = "ln_grade_subjects";
    protected $fillable = [
        "grade_id","subject_id"
    ];
}
