<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;

class AuthorizationController extends Controller
{
    public function authorization(Request $request)
    {
        $user = User::whereVkId($request->get('vk_user_id'))->first(); // Searching user with VK User ID
        if ($user && $user->vk_token === $request->get('sign'))
            if ($user->isRegistered())
                return response()->json(['status' => 'ok', 'registered' => true, 'user' => UserResource::make($user)])->setStatusCode(200);
            else
                return response()->json(['status' => 'ok', 'registered' => false, 'user' => UserResource::make($user)])->setStatusCode(200);

        $query_params = $request->all();
        $sign_params = [];

        foreach ($query_params as $name => $value) {
            if (strpos($name, 'vk_') !== 0)  // Getting parametrs that starts with vk_
                continue;
            $sign_params[$name] = $value;
        }

        ksort($sign_params); // Sorting keys
        $sign_params_query = http_build_query($sign_params); // Building query string
        $client_secret = env('VK_CLIENTSECRET'); // Getting Client Secret from ENV

        $sign = rtrim(strtr(base64_encode(hash_hmac('sha256', $sign_params_query, $client_secret, true)), '+/', '-_'), '='); // Checking sign by VK algo
        $status = $sign === $query_params['sign'];

        if (!$status)
            return response()->json([
                'status' => 'error',
                'messages' => ['Хеш не прошёл проверку на валидность']
            ])->setStatusCode(400);

        if ($user) {
            $user->update(['vk_token' => $sign]); // Updating VK Token in DB
            return response()->json(['status' => 'ok', 'registered' => true, 'user' => UserResource::make($user)])->setStatusCode(200);
        } else {
            $user = User::create([
                'vk_id' => $request->get('vk_user_id'),
                'vk_token' => $sign
            ]);
            return response()->json(['status' => 'ok', 'registered' => false, 'user' => UserResource::make($user)])->setStatusCode(200);
        }
    }
}
