<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Students extends Model
{
    use HasFactory;

    protected $table = "students";
    protected $fillable = [
        "student_name","student_tel","student_hp",
        "parent_hp",
        "school_name","school_grade",
        "abs_id","class_id","teacher_name",
        "is_live"
    ];

    public function ClassObj(){
        return $this->hasOne(Classes::class,"id", "class_id");
    }

    public function getAllAbsCodes(){
        $data = DB::table($this->table)->select('abs_id')->get();
        $results = [];
        foreach($data as $d){
            $results[] = $d->abs_id;
        }
        return $results;
    }
}
