<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('settings')->insert([
            [
                'set_code' => "PAGE_CODE",
                'set_name'  => "리스트 보여지는 개수",
                'set_value' => "20",
                'set_short_desc'    => "리스트 페이지에서 아이템들이 보여지는 개수."
            ],
            [
                'set_code' => "MAX_SCORE_CODE",
                'set_name'  => "각 시험의 최대 점수",
                'set_value' => "65",
                'set_short_desc'    => "각 시험의 최대 점수."
            ],
            [
                'set_code' => "PRESIDENT_CALL",
                'set_name'  => "대표번호",
                'set_value' => "1544-0709",
                'set_short_desc'    => "수업공지 프로그램 하단 학원 대표 전화번호."
            ]
        ]);
    }
}
