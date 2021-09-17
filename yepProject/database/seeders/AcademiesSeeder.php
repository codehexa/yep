<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('academies')->insert([
            [
                'ac_name' => "나루관",
                'ac_tel'  => "031-8003-6911",
                'ac_code' => "N"
            ],
            [
                'ac_name' => "석우관",
                'ac_tel'  => "010-1111-2222",
                'ac_code' => "W"
            ],
            [
                'ac_name' => "청계관",
                'ac_tel'  => "010-1111-2222",
                'ac_code' => "Q"
            ],
            [
                'ac_name' => "남동탄관",
                'ac_tel'  => "010-1111-2222",
                'ac_code' => "U"
            ],
            [
                'ac_name' => "영통관",
                'ac_tel'  => "010-1111-2222",
                'ac_code' => "Y"
            ],
            [
                'ac_name' => "상현관",
                'ac_tel'  => "010-1111-2222",
                'ac_code' => "K"
            ]
        ]);
    }
}
