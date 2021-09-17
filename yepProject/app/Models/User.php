<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'power','live','drop_date','academy_id','last_login',
        'zoom_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function academy(){
        return $this->belongsTo(Academies::class,'academy_id');
    }

    public function getTeacherHashTable(){
        $users = User::where('power','!=','ADMIN')->where('live','=','Y')->get();
        $data = [];
        foreach($users as $user){
            $data[$user->name."_".$user->academy_id] = $user->id;
        }

        return $data;
    }

    public function makeNewTeacher($name,$acid){
        $add = new User();
        $add->name = $name;
        $add->academy_id = $acid;
        $add->email = "tmp_".time()."@".Configurations::$EMAIL_URL;
        $add->password = Hash::make(time());
        $add->uid = $add->email;
        $add->live = "Y";
        $add->power = Configurations::$USER_POWER_TEACHER;

        try {
            $add->save();
            return $add->id;
        }catch (\Exception $exception){
            return false;
        }
    }
}
