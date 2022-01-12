<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Utils\Helper\Constants;
use Illuminate\Database\Seeder;

class AchievementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 'category_key',
        // 'category_key_entry'
        // 'comment_count'
        // 'watch_count'
        $LWA = Constants::LWA;
        $CWA = Constants::CWA;
        Achievement::insert([
            ['category_key' => $LWA,  'comment_count' => null, 'watch_count' => 1,  'category_key_entry' => 'First Lesson Watched'],
            ['category_key' => $LWA,  'comment_count' => null, 'watch_count' => 5, 'category_key_entry' => '5 Lessons Watched'],
            ['category_key' => $LWA,  'comment_count' => null, 'watch_count' => 10, 'category_key_entry' => '10 Lessons Watched'],
            ['category_key' => $LWA,  'comment_count' => null, 'watch_count' => 25, 'category_key_entry' => '25 Lessons Watched'],
            ['category_key' => $LWA,  'comment_count' => null, 'watch_count' => 50, 'category_key_entry' => '50 Lessons Watched'],
            // -----------------------------------------------------------------------------------------------------------------------
            ['category_key' => $CWA,  'comment_count' => 1, 'watch_count' => null, 'category_key_entry' => 'First Comment Written'],
            ['category_key' => $CWA,  'comment_count' => 3, 'watch_count' => null, 'category_key_entry' => '3 Comments Written'],
            ['category_key' => $CWA,  'comment_count' => 5, 'watch_count' => null, 'category_key_entry' => '5 Comments Written'],
            ['category_key' => $CWA,  'comment_count' => 10, 'watch_count' => null, 'category_key_entry' => '10 Comment Written'],
            ['category_key' => $CWA,  'comment_count' => 20, 'watch_count' => null, 'category_key_entry' => '20 Comment Written'],
        ]);
    }
}
