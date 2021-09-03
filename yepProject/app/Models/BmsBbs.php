<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsBbs extends Model
{
    use HasFactory;

    protected $table = "bms_bbs";
    protected $fillable = [
        "academy_id","us_id","us_name","bbs_title","bbs_content","bbs_type","bbs_hits","bbs_added_count"
    ];

    public function Academy(){
        return $this->hasOne(Academies::class,"id","academy_id");
    }
}
