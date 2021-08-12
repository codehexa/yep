<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BmsStudyBooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('bms_study_books')->insert([
            [
                'bsb_title' => "익힘책",
                'bsb_index'  => 0,
                'bsb_direct' => 'N'
            ],
            [
                'bsb_title' => "SDL 배부",
                'bsb_index'  => 1,
                'bsb_direct' => 'N'
            ],
            [
                'bsb_title' => "직접작성",
                'bsb_index'  => 2,
                'bsb_direct' => 'Y'
            ],
        ]);
    }
}
