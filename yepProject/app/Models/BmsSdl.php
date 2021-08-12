<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsSdl extends Model
{
    use HasFactory;

    protected $table = "bms_sdl";
    protected $fillable = [
        "bs_title","bs_index","bs_direct"
    ];
}
