<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSubjects extends Model
{
    use HasFactory;

    protected $table = "log_subjects";
    protected $fillable = [
        "sj_mode","target_id","sj_old","sj_new","writer_id","sj_field"
    ];

    public function Writer(){
        return $this->belongsTo(User::class,"writer_id");
    }
}
