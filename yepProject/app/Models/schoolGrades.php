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
        $sgradeObj = schoolGrades::where("scg_not_set",'=','Y')->get();
        $sgrade = $sgradeObj->first();

        return $sgrade->id;
    }

    public function getHashTable(){
        $data = schoolGrades::get();
        $hTable = [];
        foreach ($data as $datum){
            $hTable[$datum->scg_name] = $datum->id;
        }

        return $hTable;
    }

    public function addGrade($name){
        $sg = new schoolGrades();
        $maxIndex = schoolGrades::max('scg_index');
        $sg->scg_name = $name;
        $sg_id = -1;
        $sg->scg_index = $maxIndex;

        try {
            $sg->save();
            $sg_id = $sg->id;

        }catch (\Exception $exception){

        }

        return $sg_id;
    }
}
