<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('bms_dt')->insert([
            [
                'dt_title' => "있음"
            ],
            [
                'dt_title' => "없음"
            ]
        ]);
    }
}
