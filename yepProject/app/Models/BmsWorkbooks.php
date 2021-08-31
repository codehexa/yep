<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsWorkbooks extends Model
{
    use HasFactory;

    protected $table = "bms_workbooks";
    protected $fillable = [
        "bm_title","bm_index","bw_text"
    ];
}
