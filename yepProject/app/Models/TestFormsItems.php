<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestFormsItems extends Model
{
    use HasFactory;

    protected $table = "test_forms_items";
    protected $fillable = [
        "tf_id","sj_id","sj_index","sj_title","sj_parent_id","sj_depth","sj_has_child","sj_type","sj_max_score"
    ];
}
