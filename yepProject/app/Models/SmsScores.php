<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsScores extends Model
{
    use HasFactory;

    protected $table = "smsscores";
    protected $fillable = [
        "sg_id","writer_id","year","week","tf_id","st_id","cl_id",
        "sj_count",
        "sj_0","sj_1","sj_2","sj_3","sj_4","sj_5","sj_6","sj_7","sj_8","sj_9",
        "sj_10","sj_11","sj_12","sj_13","sj_14","sj_15","sj_16","sj_17","sj_18","sj_19",
        "opinion","sent","sent_date"
    ];

    public function Student(){
        return $this->belongsTo(Students::class, "st_id");
    }

    public function Class(){
        return $this->belongsTo(Classes::class, "cl_id");
    }
}
