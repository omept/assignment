<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\Helper\AchievementHelper;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $achvtHelper = new AchievementHelper();
        $stats = $achvtHelper->statistics($user);
        return response()->json([
            'unlocked_achievements' => $stats['unlocked_achievements'],
            'next_available_achievements' => $stats['next_available_achievements'],
            'current_badge' => $stats['current_badge'],
            'next_badge' => $stats['next_badge'],
            'remaining_to_unlock_next_badge' => $stats['remaining_to_unlock_next_badge']
        ]);
    }

    public function assignment(Request $request)
    {
        // $user = User::factory()->create();
        // return redirect()->to('/users/' . $user->id . '/achievements');
        return view('assignment');
    }
}
