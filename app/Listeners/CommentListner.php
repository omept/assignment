<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Utils\Helper\AchievementHelper;
use App\Utils\Helper\Constants;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CommentListner
{
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
     * @param CommentWritten $event
     * @return void
     */
    public function handle($event)
    {
        $achievementHelper = new AchievementHelper();
        $achievementHelper->checkAndAssign($event->comment->user, Constants::CWA);
    }
}
