<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Rules\AgeRule;
use App\Rules\DistanceRule;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Search processor
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function search(Request $request)
    {
        $validator = validator($request->all(), [
            'user' => ['required', 'exists:users,vk_id'],
            'from_age' => ['required', new AgeRule()],
            'to_age' => ['required', 'gt:from_age', new AgeRule()],
            'distance' => ['required', new DistanceRule()],
            'sex' => ['required', 'in:0,1,2']
        ]);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'messages' => $validator->getMessageBag()->all()])->setStatusCode(400);

        $cu = User::whereVkId($request->get('user'))->first();
        Auth::loginUsingId($cu->id);

        $now = Carbon::now(); // Current date
        $minbirth = $now->subYears($request->get('from_age')); // Current - years min
        $maxbirth = $now->subYears($request->get('to_age')); // Current - years max
        $distance = $request->get('distance');

        $users = User::where('sex', $request->get('sex'))
            ->whereBetween('birthday', [$minbirth, $maxbirth])->get(); // Getting all users

        $users = $users->filter(function (User $user) use ($distance, $cu) { // Filtering by distance
            $user->setAttribute('calculatedDistance', $user->distance($cu->geo));
            return $user->getAttribute('calculatedDistance') <= $distance;
        });

        $users = $users->shuffle();

        return response()->json([
            'status' => 'ok',
            'users' => UserResource::collection($users),
            'count' => $users->count()
        ])->setStatusCode(200); // Sending response
    }
}
