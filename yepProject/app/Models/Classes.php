<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $table = "classes";
    protected $fillable = [
        "class_name","ac_id","sg_id","show","class_desc","teacher_id"
    ];

    public function academy(){
        return $this->belongsTo(Academies::class, "ac_id");
    }

    public function school_grade(){
        return $this->belongsTo(schoolGrades::class, "sg_id");
    }

    public function teacher(){
        return $this->belongsTo(User::class, "teacher_id");
    }

}
