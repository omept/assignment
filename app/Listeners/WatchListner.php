<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Utils\Helper\AchievementHelper;
use App\Utils\Traits\WatchLogSaver;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WatchListner
{
    use WatchLogSaver;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param LessonWatched $event
     * @return void
     */
    public function handle($event)
    {
        $this->saveWatchLog($event->lesson, $event->user);
        $achievementHelper = new AchievementHelper();
        $achievementHelper->checkAndAssign($event->comment->user);
    }
}
