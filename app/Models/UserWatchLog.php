<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWatchLog extends Model
{
    use HasFactory;


    /**
     * The attributes that are assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'lesson_id',
    ];


    /**
     * The Lesson relationship.
     * @return Lesson
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
    /**
     * The user relationship.
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
