<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BmsWorkbookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('bms_workbooks')->insert([
            [
                'bw_title' => "추가문제",
                'bw_index' => 0,
                'bw_text'   => ", 추가 문제 풀어오기",
            ],
            [
                'bw_title' => "Workbook",
                'bw_index' => 1,
                'bw_text'   => ", Workbook 문제 풀고, 해설영상 시청 및 채점",
            ],
            [
                'bw_title' => "추가문제 및 Workbook",
                'bw_index' => 2,
                'bw_text'   => ", Workbook 문제 풀고, 해설영상 시청 및 채점, 추가문제 풀어오기",
            ],
            [
                'bw_title' => "Final Test",
                'bw_index' => 3,
                'bw_text'   => ", Final Test 문제 풀고, 해설영상 시청 및 채점",
            ],
            [
                'bw_title' => "없음",
                'bw_index' => 4,
                'bw_text'   => "",
            ],
        ]);
    }
}
