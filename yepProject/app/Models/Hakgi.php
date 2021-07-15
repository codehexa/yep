<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hakgi extends Model
{
    use HasFactory;

    protected $table = "hakgi";
    protected $fillable = [
        "year","hakgi_name","show","school_grade","weeks"
    ];

    public function SchoolGrades(){
        return $this->belongsTo(schoolGrades::class,"school_grade");
    }
}
