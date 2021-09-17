<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Academies extends Model
{
    use HasFactory;

    protected $table = "academies";
    protected $fillable = [
        "ac_name","ac_tel","ac_code"
    ];

    public function getHashTable(){
        $academies = Academies::get();
        $data = [];
        foreach($academies as $academy){
            $data[$academy->ac_code]=$academy->id;
        }

        return $data;
    }
}
