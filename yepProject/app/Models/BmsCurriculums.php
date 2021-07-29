<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsCurriculums extends Model
{
    use HasFactory;

    protected $table = "bms_curriculums";
    protected $fillable = [
        "bcur_title","bcur_index"
    ];
}
