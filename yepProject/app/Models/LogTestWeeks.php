<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogTestWeeks extends Model
{
    use HasFactory;
    protected $table = "log_test_weeks";
    protected $fillable = [
        "user_id","target_id","log_mode","log_old","log_new"
    ];
}
