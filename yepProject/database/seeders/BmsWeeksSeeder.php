<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BmsWeeksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('bms_weeks')->insert([
            [
                'bmw_title' => "Week1",
                'bmw_index' => 1,
            ],
            [
                'bmw_title' => "Week2",
                'bmw_index' => 2,
            ],
            [
                'bmw_title' => "Week3",
                'bmw_index' => 3,
            ],
            [
                'bmw_title' => "Week4",
                'bmw_index' => 4,
            ],
            [
                'bmw_title' => "Week5",
                'bmw_index' => 5,
            ],
            [
                'bmw_title' => "Week6",
                'bmw_index' => 6,
            ],
            [
                'bmw_title' => "Week7",
                'bmw_index' => 7,
            ],
            [
                'bmw_title' => "Week8",
                'bmw_index' => 8,
            ],
            [
                'bmw_title' => "Week9",
                'bmw_index' => 9,
            ],
            [
                'bmw_title' => "Week10",
                'bmw_index' => 10,
            ],
            [
                'bmw_title' => "Week11",
                'bmw_index' => 11,
            ],
            [
                'bmw_title' => "Week12",
                'bmw_index' => 12,
            ],
            [
                'bmw_title' => "Week13",
                'bmw_index' => 13,
            ],
        ]);
    }
}
