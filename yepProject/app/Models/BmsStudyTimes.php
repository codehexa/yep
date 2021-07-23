<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsStudyTimes extends Model
{
    use HasFactory;

    protected $table = "bms_study_times";
    protected $fillable = [
        "time_title","time_index"
    ];
}
