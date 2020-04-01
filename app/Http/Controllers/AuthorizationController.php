<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthorizationController extends Controller
{
    public function authorize(Request $request)
    {
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
    }
}
