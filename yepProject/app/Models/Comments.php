<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    protected $table = "comments";
    protected $fillable = [
        "scg_id","ta_id","min_score","max_score","writer_id","opinion"
    ];

    public function SchoolGrade(){
        return $this->belongsTo(schoolGrades::class, "scg_id");
    }

    public function Subject(){
        return $this->belongsTo(Subjects::class, "sj_id");
    }

    public function Writer(){
        return $this->belongsTo(User::class, "writer_id");
    }
}
