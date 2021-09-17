<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class schoolGrades extends Model
{
    use HasFactory;

    protected $table = "school_grades";
    protected $fillable = [
        "scg_name","scg_index","scg_not_set"
    ];

    public function getNotSet(){
        $sgrade = schoolGrades::where("scg_not_set",'=','Y')->first();
        return $sgrade->id;
    }
}
