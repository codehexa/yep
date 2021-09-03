<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsBbsComments extends Model
{
    use HasFactory;

    protected $table = "bms_bbs_comments";
    protected $fillable = [
        "us_id","us_name","bbs_id","bc_index","bc_comment"
    ];

    public function writer(){
        return $this->hasOne(User::class,"id","us_id");
    }

}
