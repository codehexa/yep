<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSmsPapers extends Model
{
    use HasFactory;

    protected $table = "log_smspapers";
    protected $fillable = [
        "lsp_mode","lsp_writer_id","lsp_sms_paper_id",
        "old_value","new_value","lsp_field"
    ];
}
