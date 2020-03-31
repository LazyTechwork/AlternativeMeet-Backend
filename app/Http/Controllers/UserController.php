<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $validator = validator($request->all(), [
            'from_age' => ['required', 'gte:7', 'lte:87'],
            'to_age' => ['required', 'gt:from_age', 'lte:87'],
            'sex' => ['required', 'in:0,1,2']
        ]);
    }
}
