<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculums extends Model
{
    use HasFactory;

    protected $table = "curriculums";
    protected $fillable = [
        "curri_name"
    ];
}
