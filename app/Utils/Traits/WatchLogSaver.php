<?php

namespace App\Utils\Traits;

use App\Models\Lesson;
use App\Models\User;
use App\Models\UserWatchLog;

trait WatchLogSaver
{
    private function saveWatchLog(Lesson $lesson, User $user)
    {
        $saver = [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id
        ];

        UserWatchLog::create($saver);
    }
}
