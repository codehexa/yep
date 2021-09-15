<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Academies extends Model
{
    use HasFactory;

    protected $table = "academies";
    protected $fillable = [
        "ac_name","ac_tel","ac_code"
    ];
}
