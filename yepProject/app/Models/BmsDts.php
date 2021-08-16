<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsDts extends Model
{
    use HasFactory;

    protected $table = "bms_dt";
    protected $fillable = [
        "dt_title"
    ];
}
