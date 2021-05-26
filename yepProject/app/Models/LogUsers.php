<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogUsers extends Model
{
    use HasFactory;

    protected $table = "log_users";

    protected $fillable = [
        "user_id","target_id","field_name","old_value","new_value"
    ];
}
