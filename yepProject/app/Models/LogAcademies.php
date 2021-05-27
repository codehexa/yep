<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAcademies extends Model
{
    use HasFactory;

    protected $table = "log_academies";
    protected $fillable = [
        "user_id","log_category","log_mode",
        "target_id","log_desc"
    ];

    public function writer(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function academy(){
        return $this->belongsTo(Academies::class,"target_id");
    }
}
