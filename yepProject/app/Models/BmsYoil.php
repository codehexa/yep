<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsYoil extends Model
{
    use HasFactory;

    protected $table = "bms_yoil";
    protected $fillable = [
        "yoil_title","yoil_index"
    ];
}
