<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BmsPageSettings extends Model
{
    use HasFactory;

    protected $table = "bms_page_settings";

    protected $fillable = [
        "field_name","field_index","field_function","field_tag"
    ];

}
