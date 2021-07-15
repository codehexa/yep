<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsPapers extends Model
{
    use HasFactory;

    protected $table = "sms_papers";
    protected $fillable = [
        "writer_id","ac_id","cl_id","sg_id","hg_id","tf_id",
        "year","week",
        "sp_code","sp_status","sent_date"
    ];

    public function Academy(){
        return $this->hasOne(Academies::class,"id","ac_id");
    }

    public function ClassObj(){
        return $this->hasOne(Classes::class,"id","cl_id");
    }

    public function Grade(){
        return $this->hasOne(schoolGrades::class,"id","sg_id");
    }

    public function Hakgi(){
        return $this->hasOne(Hakgi::class,"id","hg_id");
    }

    public function TestForm(){
        return $this->hasOne(TestForms::class,"id","tf_id");
    }
}
