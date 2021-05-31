<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestWeeks extends Model
{
    use HasFactory;

    protected $table = "testweeks";
    protected $fillable = [
        "year","weeks","context","show","hagki"
    ];
}
