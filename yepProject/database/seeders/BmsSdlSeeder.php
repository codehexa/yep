<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BmsSdlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('bms_sdl')->insert([
            [
                'bs_title' => "다음 등원일에 제출",
                'bs_index'  => 0,
                'bs_direct' => "N"
            ],
            [
                'bs_title' => "해당하는 수업시간에 제출",
                'bs_index'  => 1,
                'bs_direct' => "N"
            ],
            [
                'bs_title' => "직접작성",
                'bs_index'  => 2,
                'bs_direct' => "Y"
            ],
        ]);
    }
}
