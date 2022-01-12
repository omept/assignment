<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Badge::insert([
            ['title' => 'Beginner', 'achievement_count' => 0],
            ['title' => 'Intermediate', 'achievement_count' => 4],
            ['title' => 'Advanced', 'achievement_count' => 8],
            ['title' => 'Master', 'achievement_count' => 10]
        ]);
    }
}
