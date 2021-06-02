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
        "curri_id"
    ];

    public function Curriculum(){
        return $this->belongsTo(Curriculums::class,"curri_id");
    }
}
