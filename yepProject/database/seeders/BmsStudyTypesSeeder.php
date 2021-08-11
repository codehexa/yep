<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BmsStudyTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('bms_study_types')->insert([
            [
                'study_title' => "Zoom 온라인 수업",
                'study_type_index'  => 0,
                'show_zoom' => 'Y'
            ],
            [
                'study_title' => "대면 수업",
                'study_type_index'  => 1,
                'show_zoom' => 'N'
            ],
            [
                'study_title' => "수업",
                'study_type_index'  => 2,
                'show_zoom' => 'N'
            ],
        ]);
    }
}
