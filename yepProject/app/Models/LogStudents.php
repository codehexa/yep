<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogStudents extends Model
{
    use HasFactory;

    protected $table = "log_students";
    protected $fillable = [
        "ls_mode","ls_writer_id","ls_target_id",
        "ls_field","old_value","new_value"
    ];
}
