<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private function ok($data = null, $meta = null, int $code = 200) {
        $res = ['ok' => true, 'data' => $data];
        if (!is_null($meta)) $res['meta'] = $meta;
        return response()->json($res, $code);
    }

    public function show(Request $req) {
        return $this->ok(['user' => $req->user()]);
    }

    public function update(Request $req) {
        $val = $req->validate([
            'name' => 'sometimes|string|max:100',
            // tambah field lain sesuai kebutuhan
        ]);
        $user = $req->user();
        $user->fill($val)->save();

        return $this->ok(['user' => $user]);
    }
}
