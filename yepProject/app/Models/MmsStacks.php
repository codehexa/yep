<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MmsStacks extends Model
{
    use HasFactory;

    protected $table = "mms_stacks";
    protected $fillable = [
        "student_id","tel","mms_text","status","sent_date"
    ];
}
