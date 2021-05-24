<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configurations extends Model
{
    use HasFactory;

    public static $USER_POWER_ADMIN = "ADMIN";
    public static $USER_POWER_MANAGER   = "MANAGER";
    public static $USER_POWER_TEACHER   = "TEACHER";

    public static $SETTINGS_PAGE_LIMIT_CODE = "PAGE_CODE";
}
