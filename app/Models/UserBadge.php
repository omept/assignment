<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    use HasFactory;
    /**
     * The attributes that are assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'badge_id',
    ];

    /**
     * The badge relationship.
     * @return badge
     */
    public function badge()
    {
        return $this->belongsTo(Badge::class);
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
