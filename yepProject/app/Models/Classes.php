<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $table = "classes";
    protected $fillable = [
        "class_name","ac_id","sg_id","show","class_desc","teacher_id"
    ];

    public function academy(){
        return $this->belongsTo(Academies::class, "ac_id");
    }

    public function school_grade(){
        return $this->belongsTo(schoolGrades::class, "sg_id");
    }

    public function teacher(){
        return $this->belongsTo(User::class, "teacher_id");
    }

    public function getHashTable(){
        $cls = Classes::get();
        $data = [];
        foreach($cls as $cl){
            $data[$cl->class_name] = $cl->id;
        }

        return $data;
    }

    public function newClass($name,$acid,$teacher_id,$not_set_id){
        $add = new Classes();
        $add->class_name = $name;
        $add->ac_id = $acid;
        $add->sg_id = $not_set_id;
        $add->show = "Y";
        $add->teacher_id = $teacher_id;

        try {
            $add->save();
            return $add->id;
        }catch (\Exception $exception){
            return false;
        }
    }

}
