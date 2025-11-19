<?php

use App\Http\Controllers\Api\V1\UserPointController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserStreakController;
use App\Http\Controllers\Api\V1\Admin\RubricController;
use App\Http\Controllers\Api\V1\DebugAiController;
use App\Http\Controllers\Api\V1\UserStatsController;
use App\Http\Controllers\Api\V1\OAuthController;
use App\Http\Controllers\Api\V1\TokenController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\SubjectController;
use App\Http\Controllers\Api\V1\GradeLevelController;
use App\Http\Controllers\Api\V1\TopicController;
use App\Http\Controllers\Api\V1\VideoController;
use App\Http\Controllers\Api\V1\QuizController;
use App\Http\Controllers\Api\V1\AttemptController;
use App\Http\Controllers\Api\V1\VideoProgressController;
use App\Http\Controllers\Api\V1\Admin\SubjectController as AdminSubjectController;
use App\Http\Controllers\Api\V1\Admin\GradeLevelController as AdminGradeLevelController;
use App\Http\Controllers\Api\V1\Admin\TopicController as AdminTopicController;
use App\Http\Controllers\Api\V1\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Api\V1\Admin\QuizController as AdminQuizController;

Route::prefix('v1')->group(function () {
    Route::post('debug/grade', [DebugAiController::class, 'gradeSample']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::post('/auth/oauth/google/exchange', [OAuthController::class, 'exchange']);
    Route::post('/auth/oauth/google/link', [OAuthController::class, 'link'])->middleware('auth:sanctum');

    Route::post('/auth/refresh', [TokenController::class, 'refresh']);


    Route::middleware('auth:sanctum')->group(function () {

        Route::apiResource('subjects', SubjectController::class);
        Route::apiResource('grade-levels', GradeLevelController::class);
        Route::apiResource('topics', TopicController::class);
        Route::apiResource('videos', VideoController::class);
        Route::apiResource('quizzes', QuizController::class);

        Route::get('attempts', [AttemptController::class, 'index']);
        Route::post('attempts', [AttemptController::class, 'store']);
        Route::get('attempts/{attempt}', [AttemptController::class, 'show']);

        Route::post('video-progress', [VideoProgressController::class, 'storeOrUpdate']);

        Route::get('me/stats', [UserStatsController::class, 'show']);
        Route::get('me/activity-logs', [UserStatsController::class, 'activityLogs']);
        Route::post('/auth/logout', [TokenController::class, 'logout']);
        Route::get('/streak', [UserStreakController::class, 'show']);

        Route::get('points', [UserPointController::class, 'show']);

    });
});

Route::prefix('v1/admin')
    ->middleware(['auth:sanctum', 'admin'])
    ->group(function () {
        Route::apiResource('subjects', AdminSubjectController::class);
        Route::apiResource('grade-levels', AdminGradeLevelController::class);
        Route::apiResource('topics', AdminTopicController::class);
        Route::apiResource('videos', AdminVideoController::class);
        Route::apiResource('quizzes', AdminQuizController::class);
        Route::apiResource('rubrics', RubricController::class);
    });