<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LogoutResponse;
use Illuminate\Http\JsonResponse;

class CustomLogoutResponse implements LogoutResponse
{
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? new JsonResponse(['message' => 'Logged out'], 204)
            : redirect('/login');
    }
}
