<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BmsStudyTimesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('bms_study_times')->insert([
            [
                'time_title' => "2시20분",
                'time_index'  => 0,
            ],
            [
                'time_title' => "3시20분",
                'time_index'  => 1,
            ],
            [
                'time_title' => "4시20분",
                'time_index'  => 2,
            ],
            [
                'time_title' => "5시20분",
                'time_index'  => 3,
            ],
            [
                'time_title' => "5시30분",
                'time_index'  => 4,
            ],
            [
                'time_title' => "6시30분",
                'time_index'  => 5,
            ],
            [
                'time_title' => "6시40분",
                'time_index'  => 6,
            ],
            [
                'time_title' => "7시40분",
                'time_index'  => 7,
            ],
            [
                'time_title' => "10시",
                'time_index'  => 8,
            ],
            [
                'time_title' => "12시30분",
                'time_index'  => 9,
            ],
        ]);
    }
}
