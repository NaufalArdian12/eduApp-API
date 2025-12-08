<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\UserPointsRepository;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class UserPointController extends Controller
{
    public function __construct(
        private UserPointsRepository $points
    ) {
    }

    public function show(Request $request)
    {
        $user = $request->user();
        $points = $this->points->getOrCreate($user->id);
        $data = $points->toArray();
        $data['name'] = $user->name ?? '';

        return ApiResponse::ok($data);
    }
}
