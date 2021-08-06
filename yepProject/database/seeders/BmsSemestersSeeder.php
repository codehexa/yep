<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BmsSemestersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('bms_semesters')->insert([
            [
                'bs_title' => "봄",
                'bs_index'  => 0
            ],
            [
                'bs_title' => "여름",
                'bs_index'  => 1
            ],
            [
                'bs_title' => "가을",
                'bs_index'  => 2
            ],
            [
                'bs_title' => "겨울",
                'bs_index'  => 3
            ],
        ]);
    }
}
