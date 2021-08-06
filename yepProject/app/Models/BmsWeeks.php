<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsWeeks extends Model
{
    use HasFactory;

    protected $table = "bms_weeks";
    protected $fillable = [
        'bmw_title','bmw_index'
    ];
}
