<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsSheets extends Model
{
    use HasFactory;

    protected $table = "bms_sheets";
    protected $fillable = [
        "bs_title","bs_id","ac_id","sg_id","us_id","pre_week","now_week","writer_id"
    ];

    public function BmsAcademy(){
        return $this->hasOne(Academies::class,"id","ac_id");
    }
}
