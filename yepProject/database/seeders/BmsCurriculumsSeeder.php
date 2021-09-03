<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BmsCurriculumsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('bms_curriculums')->insert([
            [
                'bcur_title' => "TOEFL과정",
                'bcur_index'  => 0,
            ],
            [
                'bcur_title' => "정규학기",
                'bcur_index'  => 1,
            ],
            [
                'bcur_title' => "5주과정-A",
                'bcur_index'  => 2,
            ],
            [
                'bcur_title' => "5주과정-B",
                'bcur_index'  => 3,
            ],
            [
                'bcur_title' => "5주과정-C",
                'bcur_index'  => 4,
            ],
            [
                'bcur_title' => "5주과정-D",
                'bcur_index'  => 5,
            ],
        ]);
    }
}
