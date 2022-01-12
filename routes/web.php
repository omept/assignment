<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AchievementsController;

Route::get('/', [AchievementsController::class, 'assignment']);
Route::get('/users/{user}/achievements', [AchievementsController::class, 'index']);
