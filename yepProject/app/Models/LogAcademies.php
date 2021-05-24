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
}
