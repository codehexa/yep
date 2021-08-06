<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsSemesters extends Model
{
    use HasFactory;

    protected $table = "bms_semesters";
    protected $fillable = [
        'bs_title','bs_index'
    ];
}
