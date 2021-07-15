<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsScores extends Model
{
    use HasFactory;

    protected $table = "smsscores";
    protected $fillable = [
        "sg_id","writer_id","year","week","tf_id","st_id","cl_id","hg_id",
        "score_count",
        "score_0","score_1","score_2","score_3","score_4","score_5","score_6","score_7","score_8","score_9",
        "score_10","score_11","score_12","score_13","score_14","score_15","score_16","score_17","score_18","score_19",
        "opinion","sent","sent_date","saved_check"
    ];

    public function Student(){
        return $this->belongsTo(Students::class, "st_id");
    }

    public function Class(){
        return $this->belongsTo(Classes::class, "cl_id");
    }
}
