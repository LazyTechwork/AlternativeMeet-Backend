<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class VKAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->hasHeader('VK-Authorization'))
            return response()->json(['status' => 'error', 'messages' => ['Для использования API следует авторизоваться']])->setStatusCode(401);
        $user = User::whereVkToken($request->header('VK-Authorization'))->first();
        if ($user->exists())
            Auth::loginUsingId($user->id);
        else
            return response()->json(['status' => 'error', 'messages' => ['Данный хеш не найден в нашей базе данных, для начала использования, просим авторизоваться']])->setStatusCode(401);

        return $next($request);
    }
}
