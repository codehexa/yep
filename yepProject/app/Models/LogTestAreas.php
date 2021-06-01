<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogTestAreas extends Model
{
    use HasFactory;

    protected $table = "log_testareas";
    protected $fillable = [
        "mode","target_id","old_value","new_value"
    ];
}
