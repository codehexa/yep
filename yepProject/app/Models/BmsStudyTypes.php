<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsStudyTypes extends Model
{
    use HasFactory;

    protected $table= "bms_study_types";
    protected $fillable = [
        "study_title","study_type_index"
    ];
}
