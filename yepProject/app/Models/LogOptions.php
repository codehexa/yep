<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogOptions extends Model
{
    use HasFactory;

    protected $table = "log_options";
    protected $fillable = [
        "user_id","opt_code","opt_old_value","opt_new_value","opt_log_desc"
    ];

    public function writer(){
        return $this->belongsTo(User::class,"user_id");
    }
}
