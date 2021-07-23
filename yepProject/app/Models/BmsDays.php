<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsDays extends Model
{
    use HasFactory;

    protected $table = "bms_days";
    protected $fillable = [
        "days_title"
    ];
}
