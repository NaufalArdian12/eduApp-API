<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\UserStreakRepository;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class UserStreakController extends Controller
{
    public function __construct(
        private UserStreakRepository $repo
    ) {
    }

    public function show(Request $request)
    {
        $streak = $this->repo->getOrCreate($request->user()->id);

        return ApiResponse::ok($streak);
    }
}
