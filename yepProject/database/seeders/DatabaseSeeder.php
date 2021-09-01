<?php

namespace Database\Seeders;

use App\Models\BmsCurriculums;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            YoilSeeder::class,
            DtSeeder::class,
            BmsWeeksSeeder::class,
            SettingsSeeder::class,
            BmsSemestersSeeder::class,
            BmsStudyTypesSeeder::class,
            BmsStudyBooksSeeder::class,
            BmsSdlSeeder::class,
            BmsDaysSeeder::class,
            BmsStudyTimesSeeder::class,
            BmsCurriculumsSeeder::class,
        ]);
    }
}
