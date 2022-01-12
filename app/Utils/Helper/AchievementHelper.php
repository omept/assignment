<?php

namespace App\Utils\Helper;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserBadge;
use App\Models\UserWatchLog;
use Illuminate\Support\Facades\Log;

class AchievementHelper
{
    private User $user;
    private string $category;
    private array $achievements_from_user;
    private array $category_achievements;
    private array $next_category_achievement;
    private array $next_category_achievements;
    private array $badges;
    private array $next_badges;
    private array $next_badge;
    private array $current_badge;
    private int $user_achievement_count;

    /*
    * @param User $user
    * @param category which is either Constants::LWA or Constants::CWA
    * @return bool
    */
    public function checkAndAssign(User $user, string $category = Constants::LWA): bool
    {
        $this->user = $user;
        $this->category = $category;

        // Check if user can be assigned an achievement
        $check = $this->check();
        if ($check) {
            // If true, call assign
            $this->assign();
            return true;
        }
        return false;
    }

    private function check(): bool
    {
        $this->setAchvmntsFrmUsr();
        $this->categoryAchievements();
        $this->evalNextAchv();

        if ($this->category === Constants::LWA) {
            return $this->checkLwa();
        } else {
            return $this->checkCwa();
        }
    }

    private function setAchvmntsFrmUsr()
    {
        $cat = $this->category;
        $usrAchmnts = UserAchievement::where([
            'user_id' => $this->user->id,
        ])->whereHas('achievement', function ($query) use ($cat) {
            return $query->where('achievements.category_key', '=', $cat);
        })->with('achievement')->get()->toArray();

        $this->achievements_from_user = count($usrAchmnts) ? collect($usrAchmnts)->map(function ($item) {
            return $item['achievement'];
        })->toArray() : [];
    }

    private function categoryAchievements()
    {
        # code...
        $this->category_achievements =  Achievement::where([
            'category_key' => $this->category
        ])->get()->toArray();
    }

    private function checkLwa(): bool
    {

        $usrLesnsCnt = UserWatchLog::where([
            'user_id' => $this->user->id,
        ])->count();

        if (
            is_array($this->next_category_achievement) &&
            isset($this->next_category_achievement['watch_count']) &&
            $usrLesnsCnt == $this->next_category_achievement['watch_count']
        ) {
            return true;
        }

        return false;
    }

    private function checkCwa(): bool
    {
        $usrCmmntCnt = Comment::where([
            'user_id' => $this->user->id,
        ])->count();

        if (
            is_array($this->next_category_achievement) &&
            isset($this->next_category_achievement['comment_count']) &&
            $usrCmmntCnt == $this->next_category_achievement['comment_count']
        ) {
            return true;
        }

        return false;
    }

    private function evalNextAchv()
    {
        $achvsFrmUsr = $this->achievements_from_user;
        $achvsFrmUsrIds = array_map(function ($item) {
            return $item['id'];
        }, $achvsFrmUsr);

        $difference = collect($this->category_achievements)->filter(function ($item) use ($achvsFrmUsrIds) {
            return !in_array($item['id'], $achvsFrmUsrIds);
        });
        $this->next_category_achievement =  $difference->shift() ?? [];
        $this->next_category_achievements = array_slice($difference->toArray(), 1);
    }

    /**
     * @param $user
     * @param $cat which is either Constants::LWA or Constants::CWA
     */
    public function evalNextBadges(User $user)
    {
        // next badge
        $this->next_badge = [];
        $this->next_badges = [];
        $this->current_badge = [];
        $this->user = $user;
        $this->badges = Badge::all()->toArray();
        $usrBdgs = UserBadge::where('user_id', $this->user->id)->get()->toArray();
        $this->user_achievement_count = UserAchievement::where([
            'user_id' => $this->user->id,
        ])->count();


        // if user has no badge, assign the first badge
        if (count($usrBdgs) == 0) {
            UserBadge::create([
                'badge_id' => $this->badges[0]['id'],
                'user_id' => $this->user->id,
            ]);
            $this->current_badge  = $this->badges[0];
            $this->next_badge  = $this->badges[1];
            $this->next_badges =  array_slice($this->badges, 1);
            event(new BadgeUnlocked($this->current_badge['title'], $this->user));
        } else {

            $badge_index = UserBadge::where('user_id', $this->user->id)->count();
            $this->current_badge  = $this->badges[$badge_index - 1];

            if ($this->badges[$badge_index]['achievement_count'] == $this->user_achievement_count) {
                UserBadge::create([
                    'badge_id' => $this->badges[$badge_index]['id'],
                    'user_id' => $this->user->id,
                ]);
                $this->current_badge  = $this->badges[$badge_index];

                // next badge
                if (isset($this->badges[$badge_index + 1])) {
                    $this->next_badge  = $this->badges[$badge_index + 1];
                    $this->next_badges =  array_slice($this->badges, $badge_index + 1);
                }
                event(new BadgeUnlocked($this->badges[$badge_index]['title'], $this->user));
            } else {
                // next badge 
                $this->next_badge = $this->badges[$badge_index];
                $this->next_badges =  array_slice($this->badges, $badge_index);
            }
        }
    }

    private function assign()
    {
        UserAchievement::create(
            [
                'user_id' => $this->user->id,
                'achievement_id' => $this->next_category_achievement['id']
            ]
        );
        $this->dispatchEvent();
        $this->evalNextBadges($this->user);
    }

    private function dispatchEvent()
    {
        event(new AchievementUnlocked(
            $this->next_category_achievement['category_key_entry'],
            $this->user
        ));
    }

    public function statistics(User $user)
    {
        $this->user = $user;

        $nxt_a_achvmnts = [];
        $unlkd_achvmnts = [];
        $cts = [Constants::LWA, Constants::CWA];
        for ($i = 0; $i < count($cts); $i++) {
            // call check on each category
            $this->category = $cts[$i];
            $this->check();
            $unlkd_achvmnts = array_merge($unlkd_achvmnts, collect($this->achievements_from_user)->map(function ($item) {
                return $item['category_key_entry'];
            })->toArray());
            $nxt_a_achvmnts[] = $this->next_category_achievement['category_key_entry'];
        }

        $this->evalNextBadges($this->user);
        $nxt_bgs = collect($this->next_badges)->map(function ($item) {
            return $item['title'];
        })->toArray();

        $nxt_bdg_achv_cnt = isset($this->next_badge['achievement_count']) ? $this->next_badge['achievement_count'] : 0; // next badge achievement count
        return [
            'unlocked_achievements' => $unlkd_achvmnts,
            'next_available_achievements' => $nxt_a_achvmnts,
            'current_badge' => isset($this->current_badge['title']) ? $this->current_badge['title'] : '',
            'next_badge' => isset($this->next_badge['title']) ? $this->next_badge['title'] : '',
            'next_badges' => $nxt_bgs,
            'remaining_to_unlock_next_badge' => $nxt_bdg_achv_cnt - $this->user_achievement_count,
        ];
    }
}
