<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{

    use HasFactory;

    /**
     * The attributes that are assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'achievement_id',
    ];

    /**
     * The achievement relationship.
     * @return Achievement
     */
    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
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
