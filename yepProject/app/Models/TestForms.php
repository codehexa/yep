<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestForms extends Model
{
    use HasFactory;

    protected $table = "test_forms";
    protected $fillable = [
        "writer_id","form_title","ac_id","grade_id","items_count","tf_desc"
    ];

    public function Grades(){
        return $this->belongsTo(schoolGrades::class,"grade_id");
    }

    public function Writer(){
        return $this->belongsTo(User::class, "writer_id");
    }

    public function Academy(){
        return $this->belongsTo(Academies::class,"ac_id");
    }
}
