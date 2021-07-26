<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsSheets extends Model
{
    use HasFactory;

    protected $table = "bms_sheets";
    protected $fillable = [
        "bs_title","sg_id"
    ];
}
