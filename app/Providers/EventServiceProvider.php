<?php

namespace App\Providers;

use App\Events\AchievementUnlocked;
use App\Events\LessonWatched;
use App\Events\CommentWritten;
use App\Listeners\CommentListner;
use App\Listeners\WatchListner;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AchievementUnlocked::class => [
            // 
        ],
        CommentWritten::class => [
            //
            CommentListner::class
        ],
        LessonWatched::class => [
            //
            WatchListner::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
