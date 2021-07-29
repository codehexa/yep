<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsHworks extends Model
{
    use HasFactory;

    protected $table = "bms_hworks";
    protected $fillable = [
        'hwork_sgid','hwork_class','hwork_content','hwork_dt','hwork_book','hwork_output_first','hwork_output_second',
        'hwork_opt1','hwork_opt2','hwork_opt3','hwork_opt4','hwork_opt5'
    ];

    public function SchoolGrade(){
        return $this->hasOne(schoolGrades::class, "id","hwork_sgid");
    }
}
