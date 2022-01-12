<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\User;
use App\Models\UserWatchLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserWatchLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserWatchLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'lesson_id' => Lesson::factory()->create(),
            'user_id' => User::factory()->create(),
        ];
    }
}
