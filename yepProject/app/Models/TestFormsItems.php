<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestFormsItems extends Model
{
    use HasFactory;

    protected $table = "test_forms_items";
    protected $fillable = [
        "tf_id","curri_id","sj_id","tf_index"
    ];
}
