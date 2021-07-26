<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YoilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('bms_yoil')->insert([
            [
                'yoil_title' => "월요일",
                'yoil_index' => 0,
            ],
            [
                'yoil_title' => "화요일",
                'yoil_index' => 1,
            ],
            [
                'yoil_title' => "수요일",
                'yoil_index' => 2,
            ],
            [
                'yoil_title' => "목요일",
                'yoil_index' => 3,
            ],
            [
                'yoil_title' => "금요일",
                'yoil_index' => 4,
            ],
            [
                'yoil_title' => "토요일",
                'yoil_index' => 5,
            ],
            [
                'yoil_title' => "일요일",
                'yoil_index' => 6,
            ],
            [
                'yoil_title' => "휴원",
                'yoil_index' => 7,
            ],
        ]);
    }
}
