<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LnCurriSubject extends Model
{
    use HasFactory;

    protected $table = "ln_curri_subject";
    protected $fillable = [
        "curri_id","subject_id"
    ];

    public function Curri(){
        return $this->belongsTo(Curriculums::class,"curri_id");
    }

    public function Subject(){
        return $this->belongsTo(Subjects::class, "subject_id");
    }
}
