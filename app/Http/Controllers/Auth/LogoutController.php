<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class LogoutController extends Controller
{
    public function delete()
    {
        foreach (auth()->user()->tokens as $token):
            $token->revoke();
        endforeach;

        return response()->json([]);
    }
}
