<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogScores extends Model
{
    use HasFactory;
    protected $table = "log_scores";
    protected $fillable = [
        "ss_mode","ss_writer_id","ss_target_id",
        "old_value","new_value"
    ];
}
