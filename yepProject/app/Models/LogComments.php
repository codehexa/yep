<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogComments extends Model
{
    use HasFactory;

    protected $table = "log_comments";

    protected $fillable = [
        "cm_mode","target_id","cm_old","cm_new","writer_id","cm_field"
    ];
}
