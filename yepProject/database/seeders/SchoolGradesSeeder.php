<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolGradesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('school_grades')->insert([
            [
                'scg_name' => "미지정",
                'scg_index'  => "1",
                'scg_not_set' => "Y"
            ],
        ]);
    }
}
