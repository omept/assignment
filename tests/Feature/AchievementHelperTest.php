<?php

namespace Tests\Feature;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\Comment;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserBadge;
use App\Models\UserWatchLog;
use App\Utils\Helper\AchievementHelper;
use App\Utils\Helper\Constants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AchievementHelperTest extends TestCase
{
    public function resetDb()
    {
        $this->artisan('migrate:fresh', [
            '--seed' => '1'
        ]);
    }

    public function test_n_written_comment_achievements($reset_db = true, $user = null)
    {
        if ($reset_db) {
            $this->resetDb();
        }
        Event::fake([
            AchievementUnlocked::class,
        ]);
        $achievements = Achievement::where('category_key', Constants::CWA)->get()->toArray();
        $nPool = collect($achievements)->map(function ($item) {
            return $item['comment_count'];
        })->toArray();

        $index = array_rand($nPool);
        $n = $nPool[$index];

        $user = $user ?? User::factory()->create();
        Comment::factory()->count($n)->create(['user_id' => $user->id]);
        $pre_achievement = [];
        for ($i = 0; $i < $index; $i++) {
            $pre_achievement[] = [
                'achievement_id' =>  $achievements[$i]['id'],
                'user_id' => $user->id
            ];
        }
        // attach achievements to user
        if ($pre_achievement) {
            UserAchievement::insert($pre_achievement);
        }

        // check and assign achievement
        $achvtHelper = new AchievementHelper();
        $achvtHelper->checkAndAssign($user, Constants::CWA);

        Event::assertDispatched(AchievementUnlocked::class);


        // check if achievement is assigned
        $this->assertTrue(UserAchievement::where([
            'achievement_id' => $achievements[$index]['id'],
            'user_id' => $user->id,
        ])->exists());
    }
    public function test_n_watched_lessons_achievements($reset_db = true, $user = null)
    {
        if ($reset_db) {
            $this->resetDb();
        }
        Event::fake([
            AchievementUnlocked::class,
        ]);
        $cat = Constants::LWA;
        $achievements = Achievement::where('category_key', $cat)->get()->toArray();
        $nPool = collect($achievements)->map(function ($item) {
            return $item['watch_count'];
        })->toArray();
        // $nPool = [
        // 1,
        // 5,
        // 15,
        // 25,
        // 50
        // ];

        $index = array_rand($nPool);
        $n = $nPool[$index];


        $user = $user ?? User::factory()->create();
        UserWatchLog::factory()->count($n)->create(['user_id' => $user->id]);
        $pre_achievement = [];
        for ($i = 0; $i < $index; $i++) {
            $pre_achievement[] = [
                'achievement_id' =>  $achievements[$i]['id'],
                'user_id' => $user->id
            ];
        }
        // attach achievements to user
        if ($pre_achievement) {
            UserAchievement::insert($pre_achievement);
        }

        // check and assign achievement
        $achvtHelper = new AchievementHelper();
        $achvtHelper->checkAndAssign($user, $cat);

        Event::assertDispatched(AchievementUnlocked::class);

        // check if achievement is assigned
        $this->assertTrue(UserAchievement::where([
            'achievement_id' => $achievements[$index]['id'],
            'user_id' => $user->id,
        ])->exists());
    }
    public function test_n_badges_achievements($reset_db = true, $user = null)
    {
        if ($reset_db) {
            $this->resetDb();
        }
        Event::fake([
            BadgeUnlocked::class,
        ]);
        $badges = Badge::all()->toArray();
        $nPool = collect($badges)->map(function ($item) {
            return $item['achievement_count'];
        })->toArray();
        // $nPool = [
        //     0,
        //     4,
        //     8,
        //     10
        // ];
        // $index = 3;
        $index = array_rand($nPool);
        $n = $nPool[$index];


        $user = $user ?? User::factory()->create();
        if ($n > 0) {
            UserAchievement::factory()->count($n)->create(['user_id' => $user->id]);
        }

        $pre_badges = [];
        for ($i = 0; $i < $index; $i++) {
            $pre_badges[] = [
                'badge_id' =>  $badges[$i]['id'],
                'user_id' => $user->id
            ];
        }
        // attach achievements to user
        if ($pre_badges) {
            UserBadge::insert($pre_badges);
        }

        // check and assign achievement
        $achvtHelper = new AchievementHelper();
        $achvtHelper->evalNextBadges($user);

        Event::assertDispatched(BadgeUnlocked::class);

        $this->assertTrue(UserBadge::where([
            'badge_id' => $badges[$index]['id'],
            'user_id' => $user->id,
        ])->exists());
    }

    public function test_user_stats()
    {
        $this->resetDb();
        $user = User::factory()->create();
        $achvtHelper = new AchievementHelper();

        // alternate between watching and commenting (or use both to set state)
        $this->test_n_watched_lessons_achievements(false, $user);
        $this->test_n_written_comment_achievements(false, $user);

        // uncomment next line to simulate badge behavior. 
        // $this->test_n_badges_achievements(false, $user);


        // Log::info($achvtHelper->statistics($user));
        $this->assertTrue(count($achvtHelper->statistics($user)) == 6);
    }
}
