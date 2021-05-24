<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $table = "settings";
    protected $fillable = [
        "set_code","set_name","set_value","set_short_desc"
    ];
}
