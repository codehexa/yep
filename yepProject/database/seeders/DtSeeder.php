<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('bms_dt')->insert([
            [
                'dt_title' => "단어만",
                'dt_text'   => "",
            ],
            [
                'dt_title' => "SDL 내용 및 단어",
                'dt_text'   => "내용 및",
            ]
        ]);
    }
}
