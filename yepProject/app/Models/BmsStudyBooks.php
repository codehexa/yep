<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsStudyBooks extends Model
{
    use HasFactory;

    protected $table = "bms_study_books";
    protected $fillable = [
        "bsb_title","bsb_index","bsb_direct"
    ];
}
