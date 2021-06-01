<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAreas extends Model
{
    use HasFactory;

    protected $table = "test_areas";
    protected $fillable = [
        "ta_name","parent_id","ta_code","ta_child_count","ta_max_score"
    ];

    public function testParent(){
        return $this->belongsTo(TestAreas::class,"parent_id");
    }
}
