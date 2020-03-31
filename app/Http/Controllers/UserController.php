<?php

namespace App\Http\Controllers;

use App\Rules\AgeRule;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $validator = validator($request->all(), [
            'from_age' => ['required', new AgeRule()],
            'to_age' => ['required', 'gt:from_age', new AgeRule()],
            'distance' => ['required', 'integer'],
            'sex' => ['required', 'in:0,1,2']
        ]);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'messages' => $validator->getMessageBag()->all()])->setStatusCode(400);
    }
}
