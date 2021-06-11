<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogTestForms extends Model
{
    use HasFactory;

    protected $table = "log_testforms";
    protected $fillable = [
        "lt_mode","lt_id","lt_old","lt_new","writer_id","lt_field"
    ];
}
