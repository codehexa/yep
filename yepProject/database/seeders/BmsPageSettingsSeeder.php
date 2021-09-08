<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BmsPageSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('bms_page_settings')->insert([
            [
                'field_name' => "인사말",
                'field_index'  => 0,
                'field_function' => "처음아무말. 정성을 다하는 이언어학원 :CLASS_NAME: 반 담임 :TEACHER_NAME: 쌤입니다.  :SEMESTER: :CURRICULUM: :NOW_WEEK: :STUDY_TYPE: 공지입니다."
            ],
            [
                'bs_title' => "공지사항",
                'bs_index'  => 1,
                'bs_direct' => "(공지) 지각이나 결석이 있을 경우에는 담임선생님이 아닌 학원 데스크로 전화 부탁드립니다.\r\n담임선생님이 수업중인 경우 전화연결이 어려울 수 있습니다."
            ],
            [
                'bs_title' => "학원 관련 전화번호",
                'bs_index'  => 2,
                'bs_direct' => ":ACADEMY_NAME: :ACADEMY_TEL: 대표번호  :ACADEMY_ALL_TEL:"
            ],
        ]);
    }
}
