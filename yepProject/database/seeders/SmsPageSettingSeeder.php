<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmsPageSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('sms_page_settings')->insert([
            [
                'greetings' => "여기에 인사말을 입력하세요",
                "teacher_title" => "선생님 말씀 입니다",
                "sps_opt_1"   => "'학생이름' 성적표 링크입니다",
                "sps_opt_2" => "페이지 하단에 입력되는 내용입니다",
            ],
        ]);
    }
}
