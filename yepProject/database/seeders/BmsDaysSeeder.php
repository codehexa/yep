<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BmsDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bms_days')->insert([
            [
                'days_title' => "화목",
                'days_index'  => 0,
                'days_count' => "2"
            ],
            [
                'days_title' => "월수",
                'days_index'  => 1,
                'days_count' => "2"
            ],
            [
                'days_title' => "월금",
                'days_index'  => 2,
                'days_count' => "2"
            ],
            [
                'days_title' => "수금",
                'days_index'  => 3,
                'days_count' => "2"
            ],
            [
                'days_title' => "월수금",
                'days_index'  => 4,
                'days_count' => "3"
            ],
            [
                'days_title' => "금토",
                'days_index'  => 5,
                'days_count' => "2"
            ],
            [
                'days_title' => "토일",
                'days_index'  => 6,
                'days_count' => "2"
            ],
        ]);
    }
}
